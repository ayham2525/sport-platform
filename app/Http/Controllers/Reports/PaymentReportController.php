<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentReportRequest;
use App\Models\Academy;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Schema;

class PaymentReportController extends Controller
{
    public function index(PaymentReportRequest $request)
    {
        // Gate the report
        if (!PermissionHelper::hasPermission('view', Payment::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        // Normalize academy_ids from users.academy_id (JSON → array)
        $academyIds = $user->academy_id;
        if (!is_array($academyIds)) {
            $academyIds = $academyIds ? (json_decode($academyIds, true) ?: []) : [];
        }

        // -------- Collect filters (for the form + view) --------
        $filters = [
            'date_from'         => $request->input('date_from'),
            'date_to'           => $request->input('date_to'),
            'status'            => $request->input('status'),
            'category'          => $request->input('category'),
            'branch_id'         => $request->input('branch_id'),
            'academy_id'        => $request->input('academy_id'),
            'program_id'        => $request->input('program_id'),
            'player_id'         => $request->input('player_id'),
            'payment_method_id' => $request->input('payment_method_id'),
            'reset_search'      => $request->input('reset_search'),
            'per_page'          => (int) $request->input('per_page', 25),
        ];
        $perPage = $filters['per_page'] ?: 25;

        // -------- Base query --------
        $q = Payment::query()
            ->with(['player.user', 'program', 'branch', 'academy', 'paymentMethod']);

        // -------- Role scoping --------
        switch ($user->role) {
            case 'full_admin':
                // no scoping
                break;

            case 'system_admin':
                if ($user->system_id && Schema::hasColumn((new Payment())->getTable(), 'system_id')) {
                    $q->where('system_id', $user->system_id);
                }
                break;

            case 'branch_admin':
            case 'coach':
                if ($user->branch_id) {
                    $q->where('branch_id', $user->branch_id);
                } else {
                    $q->whereRaw('1=0');
                }
                break;

            case 'academy_admin':
                if (!empty($academyIds)) {
                    $q->whereIn('academy_id', $academyIds);
                } else {
                    $q->whereRaw('1=0');
                }
                break;

            case 'player':
                $q->whereHas('player', fn ($p) => $p->where('user_id', $user->id));
                break;

            default:
                abort(403);
        }

        // -------- Filters from request --------
        $dateColumn = Schema::hasColumn((new Payment())->getTable(), 'payment_date')
            ? 'payment_date'
            : 'created_at';

        if (!empty($filters['date_from'])) {
            $q->whereDate($dateColumn, '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate($dateColumn, '<=', $filters['date_to']);
        }

        $q->when($filters['status'],            fn ($qq) => $qq->where('status', $filters['status']))
          ->when($filters['category'],          fn ($qq) => $qq->where('category', $filters['category']))
          ->when($filters['branch_id'],         fn ($qq) => $qq->where('branch_id', $filters['branch_id']))
          ->when($filters['academy_id'],        fn ($qq) => $qq->where('academy_id', $filters['academy_id']))
          ->when($filters['program_id'],        fn ($qq) => $qq->where('program_id', $filters['program_id']))
          ->when($filters['player_id'],         fn ($qq) => $qq->where('player_id', $filters['player_id']))
          ->when($filters['payment_method_id'], fn ($qq) => $qq->where('payment_method_id', $filters['payment_method_id']))
          ->when($filters['reset_search'],      fn ($qq) => $qq->where('reset_number', 'like', '%'.$filters['reset_search'].'%'));

        // -------- Aggregates --------
        $totals = (clone $q)->selectRaw('
            COALESCE(SUM(base_price),0)       as base_price_sum,
            COALESCE(SUM(discount),0)         as discount_sum,
            COALESCE(SUM(vat_amount),0)       as vat_sum,
            COALESCE(SUM(total_price),0)      as total_sum,
            COALESCE(SUM(paid_amount),0)      as paid_sum,
            COALESCE(SUM(remaining_amount),0) as remaining_sum
        ')->first();

        $statusCounts = (clone $q)
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        // -------- Counts by Payment Method (within the current filters) --------
        $methodCounts = (clone $q)
            ->selectRaw('payment_method_id, COUNT(*) as c')
            ->groupBy('payment_method_id')
            ->pluck('c', 'payment_method_id')
            ->toArray();

        $methodNames = PaymentMethod::whereIn('id', array_filter(array_keys($methodCounts)))
            ->pluck('name', 'id')
            ->toArray();

        // -------- Export CSV --------
        if ($request->input('export') === 'csv') {
            $filename = 'payments_report_'.now()->format('Ymd_His').'.csv';
            $stream = function () use ($q) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'ID','Category','Status','Payment Date','Player',
                    'Program','Branch','Academy','Payment Method',
                    'Base','Discount','VAT','Total','Paid','Remaining',
                    'Currency','Receipt/Reset #'
                ]);

                (clone $q)->orderBy('id')->chunk(1000, function ($rows) use ($handle) {
                    foreach ($rows as $p) {
                        fputcsv($handle, [
                            $p->id,
                            $p->category,
                            $p->status,
                            optional($p->payment_date)->format('Y-m-d'),
                            optional($p->player->user)->name,
                            optional($p->program)->name ?? '',
                            optional($p->branch)->name ?? '',
                            optional($p->academy)->name_en ?? '',
                            optional($p->paymentMethod)->name ?? '',
                            $p->base_price,
                            $p->discount,
                            $p->vat_amount,
                            $p->total_price,
                            $p->paid_amount,
                            $p->remaining_amount,
                            $p->currency,
                            $p->reset_number,
                        ]);
                    }
                });

                fclose($handle);
            };

            return response()->streamDownload($stream, $filename, [
                'Content-Type' => 'text/csv',
            ]);
        }

        // -------- Pagination --------
        $payments = $q->latest()->paginate($perPage)->appends($request->query());

        // -------- Dropdown options (Branch / Academy / Payment Method) by role --------
        [$branchOptions, $academyOptions] = $this->buildScopedOptions($user, $academyIds);
        $paymentMethodOptions = PaymentMethod::orderBy('name')->pluck('name', 'id');

        // -------- AJAX partial (no filters form) --------
        if ($request->ajax()) {
            return view('admin.reports.payments.partials.results', compact(
                'payments', 'totals', 'statusCounts', 'methodCounts', 'methodNames'
            ));
        }

        // -------- Full page --------
        return view('admin.reports.payments.index', compact(
            'payments',
            'totals',
            'statusCounts',
            'filters',
            'branchOptions',
            'academyOptions',
            'paymentMethodOptions',
            'methodCounts',
            'methodNames'
        ));
    }

    /**
     * Build Branch/Academy dropdown options based on the user's role and scope.
     *
     * @return array [branchOptions, academyOptions] as [id => name_en]
     */
    private function buildScopedOptions($user, array $academyIds): array
    {
        $branchOptions  = collect();
        $academyOptions = collect();

        switch ($user->role) {
            case 'full_admin':
                $branchOptions  = Branch::orderBy('name')->pluck('name', 'id');
                $academyOptions = Academy::orderBy('name_en')->pluck('name_en', 'id');
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $branchOptions  = Branch::where('system_id', $user->system_id)
                        ->orderBy('name')->pluck('name', 'id');

                    if (Schema::hasColumn((new Academy())->getTable(), 'system_id')) {
                        $academyOptions = Academy::where('system_id', $user->system_id)
                            ->orderBy('name_en')->pluck('name_en', 'id');
                    } else {
                        $academyOptions = Academy::whereIn('branch_id', $branchOptions->keys())
                            ->orderBy('name_en')->pluck('name_en', 'id');
                    }
                }
                break;

            case 'branch_admin':
            case 'coach':
                if ($user->branch_id) {
                    $branchOptions  = Branch::where('id', $user->branch_id)
                        ->orderBy('name')->pluck('name', 'id');

                    $academyOptions = Academy::where('branch_id', $user->branch_id)
                        ->orderBy('name_en')->pluck('name_en', 'id');
                }
                break;

            case 'academy_admin':
                if (!empty($academyIds)) {
                    $academyOptions = Academy::whereIn('id', $academyIds)
                        ->orderBy('name_en')->pluck('name_en', 'id');

                    $branchIds      = Academy::whereIn('id', $academyIds)->pluck('branch_id')->unique();
                    $branchOptions  = Branch::whereIn('id', $branchIds)
                        ->orderBy('name')->pluck('name', 'id');
                }
                break;

            case 'player':
                // No global dropdowns
                break;
        }

        return [$branchOptions, $academyOptions];
    }
}
