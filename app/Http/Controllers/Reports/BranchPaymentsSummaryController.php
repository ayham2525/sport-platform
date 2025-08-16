<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Branch;
use App\Models\Academy;
use App\Models\System;
use Carbon\Carbon;

// Excel
use App\Exports\BranchSummaryWorkbook;
use App\Exports\ArraySheet;
use Maatwebsite\Excel\Facades\Excel;

class BranchPaymentsSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['full_admin', 'system_admin'])) {
            abort(403);
        }
        if (!PermissionHelper::hasPermission('view', Payment::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        // Filters
        $selectedSystemId = $user->role === 'system_admin'
            ? ($user->system_id ?: null)
            : $request->input('system_id');

        $selectedBranchId = $request->input('branch_id');

        $dateFrom = $request->input('date_from') ?: Carbon::now('Asia/Dubai')->toDateString();
        $dateTo   = $request->input('date_to')   ?: Carbon::now('Asia/Dubai')->toDateString();

        // Systems & Branches
        $systems = $user->role === 'full_admin'
            ? System::orderBy('name')->get(['id','name'])
            : System::where('id', $selectedSystemId)->get(['id','name']);

        $branches = collect();
        if ($selectedSystemId) {
            $branches = Branch::where('system_id', $selectedSystemId)
                ->orderBy('name')->get(['id','name']);
        }

        // Payment date column
        $paymentTable = (new Payment())->getTable();
        $dateColumn   = Schema::hasColumn($paymentTable, 'payment_date') ? 'payment_date' : 'created_at';

        // Payment Methods (dynamic columns)
        $pmTable = (new PaymentMethod())->getTable();
        $paymentMethods = PaymentMethod::query()
            ->when(Schema::hasColumn($pmTable, 'is_active'), fn($q) => $q->where('is_active', 1))
            ->orderBy('name')
            ->get(['id','name','name_ar','name_ur']);
        $methodIds = $paymentMethods->pluck('id')->all();

        // ---------- Branch-level pivot ----------
        $branchRows = collect();
        if ($selectedSystemId) {
            $rows = Payment::query()
                ->from("$paymentTable as p")
                ->selectRaw('p.branch_id, p.payment_method_id, COALESCE(SUM(p.paid_amount),0) as sum_paid')
                ->where('p.system_id', $selectedSystemId)
                ->whereIn('p.payment_method_id', $methodIds)
                ->whereDate("p.$dateColumn", '>=', $dateFrom)
                ->whereDate("p.$dateColumn", '<=', $dateTo)
                ->groupBy('p.branch_id', 'p.payment_method_id')
                ->get();

            $byBranch = $rows->groupBy('branch_id')->map(fn($g) => $g->keyBy('payment_method_id'));

            // expired subscribers per branch (optional)
            $expiredByBranch = collect();
            if (
                Schema::hasTable('player_programs') &&
                Schema::hasColumn('player_programs', 'end_date') &&
                Schema::hasColumn('player_programs', 'player_id') &&
                Schema::hasColumn('player_programs', 'branch_id')
            ) {
                $expiredByBranch = DB::table('player_programs')
                    ->selectRaw('branch_id, COUNT(DISTINCT player_id) as expired_count')
                    ->whereBetween('end_date', [$dateFrom, $dateTo])
                    ->groupBy('branch_id')
                    ->get()->pluck('expired_count', 'branch_id');
            }

            $branchRows = $branches->map(function ($b) use ($byBranch, $methodIds, $expiredByBranch) {
                $methodSums = [];
                foreach ($methodIds as $mid) {
                    $methodSums[$mid] = (float) optional(optional($byBranch->get($b->id))->get($mid))->sum_paid ?? 0.0;
                }
                return (object) [
                    'branch_id'    => $b->id,
                    'branch_name'  => $b->name,
                    'methods'      => $methodSums,
                    'total_income' => array_sum($methodSums),
                    'expired'      => (int) ($expiredByBranch[$b->id] ?? 0),
                ];
            });
        }

        // ---------- Academy-level pivot (if branch selected) ----------
        $academyRows = collect();
        if ($selectedSystemId && $selectedBranchId) {
            $acadSums = Payment::query()
                ->from("$paymentTable as p")
                ->selectRaw('p.academy_id, p.payment_method_id, COALESCE(SUM(p.paid_amount),0) as sum_paid')
                ->where('p.system_id', $selectedSystemId)
                ->where('p.branch_id', $selectedBranchId)
                ->whereIn('p.payment_method_id', $methodIds)
                ->whereDate("p.$dateColumn", '>=', $dateFrom)
                ->whereDate("p.$dateColumn", '<=', $dateTo)
                ->groupBy('p.academy_id', 'p.payment_method_id')
                ->get();

            $byAcademy = $acadSums->groupBy('academy_id')->map(fn($g) => $g->keyBy('payment_method_id'));
            $academies = Academy::where('branch_id', $selectedBranchId)->orderBy('name_en')->get(['id','name_en']);

            $expiredByAcademy = collect();
            if (
                Schema::hasTable('player_programs') &&
                Schema::hasColumn('player_programs', 'end_date') &&
                Schema::hasColumn('player_programs', 'player_id') &&
                Schema::hasColumn('player_programs', 'academy_id')
            ) {
                $expiredByAcademy = DB::table('player_programs')
                    ->selectRaw('academy_id, COUNT(DISTINCT player_id) as expired_count')
                    ->where('branch_id', $selectedBranchId)
                    ->whereBetween('end_date', [$dateFrom, $dateTo])
                    ->groupBy('academy_id')
                    ->get()->pluck('expired_count', 'academy_id');
            }

            $academyRows = $academies->map(function ($a) use ($byAcademy, $methodIds, $expiredByAcademy) {
                $methodSums = [];
                foreach ($methodIds as $mid) {
                    $methodSums[$mid] = (float) optional(optional($byAcademy->get($a->id))->get($mid))->sum_paid ?? 0.0;
                }
                return (object) [
                    'academy_id'    => $a->id,
                    'academy_name'  => $a->name_en,
                    'methods'       => $methodSums,
                    'total_income'  => array_sum($methodSums),
                    'expired'       => (int) ($expiredByAcademy[$a->id] ?? 0),
                ];
            });
        }

        // ---------- Export to Excel (or CSV fallback) ----------
        if ($request->input('export') === 'excel') {
            // Build headers using current locale for method names
            $methodHeaders = $paymentMethods->map(function ($m) {
                return app()->getLocale() === 'ar'
                    ? ($m->name_ar ?? $m->name)
                    : (app()->getLocale() === 'ur'
                        ? ($m->name_ur ?? $m->name)
                        : $m->name);
            })->values()->all();

            // Branch sheet
            $branchHeader = array_merge(
                [__('reports.branch_summary.table.branch'),
                 __('reports.branch_summary.table.total_income'),
                 __('reports.branch_summary.table.expired')],
                $methodHeaders
            );
            $branchSheet = [$branchHeader];
            foreach ($branchRows as $r) {
                $row = [
                    $r->branch_name,
                    round($r->total_income, 2),
                    (int)$r->expired,
                ];
                foreach ($paymentMethods as $m) {
                    $row[] = round($r->methods[$m->id] ?? 0, 2);
                }
                $branchSheet[] = $row;
            }

            // Academy sheet (optional)
            $academySheet = null;
            if ($selectedBranchId && $academyRows->count()) {
                $academyHeader = array_merge(
                    [__('reports.branch_summary.table.academy'),
                     __('reports.branch_summary.table.total_income'),
                     __('reports.branch_summary.table.expired')],
                    $methodHeaders
                );
                $academySheet = [$academyHeader];
                foreach ($academyRows as $r) {
                    $row = [
                        $r->academy_name,
                        round($r->total_income, 2),
                        (int)$r->expired,
                    ];
                    foreach ($paymentMethods as $m) {
                        $row[] = round($r->methods[$m->id] ?? 0, 2);
                    }
                    $academySheet[] = $row;
                }
            }

            $fileName = 'branch_summary_'.now()->format('Ymd_His');
            $dateRangeLabel = $dateFrom.' → '.$dateTo;

            if (class_exists(\Maatwebsite\Excel\Excel::class)) {
                // Real XLSX workbook
                $workbook = new BranchSummaryWorkbook($branchSheet, $academySheet, $dateRangeLabel);
                return Excel::download($workbook, $fileName.'.xlsx');
            }

            // Fallback: CSV (branches sheet only)
            $stream = function () use ($branchSheet) {
                $handle = fopen('php://output', 'w');
                foreach ($branchSheet as $line) {
                    fputcsv($handle, $line);
                }
                fclose($handle);
            };
            return response()->streamDownload($stream, $fileName.'.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }

        return view('admin.reports.payments.branch_summary', compact(
            'systems',
            'branches',
            'paymentMethods',
            'selectedSystemId',
            'selectedBranchId',
            'dateFrom',
            'dateTo',
            'branchRows',
            'academyRows'
        ));
    }
}
