<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UniformReportRequest;
use App\Models\Academy;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Player;
use App\Models\UniformRequest;
use Illuminate\Support\Facades\Schema;

class UniformReportController extends Controller
{
    public function index(UniformReportRequest $request)
    {
        // Gate
        if (!PermissionHelper::hasPermission('view', UniformRequest::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        // Normalize academy_ids (JSON → array)
        $academyIds = $user->academy_id;
        if (!is_array($academyIds)) {
            $academyIds = $academyIds ? (json_decode($academyIds, true) ?: []) : [];
        }

        // Collect filters for the form/view
        $filters = [
            'date_from'      => $request->input('date_from'),
            'date_to'        => $request->input('date_to'),
            'status'         => $request->input('status'),
            'branch_status'  => $request->input('branch_status'),
            'office_status'  => $request->input('office_status'),
            'branch_id'      => $request->input('branch_id'),
            'academy_id'     => $request->input('academy_id'),
            'player_id'      => $request->input('player_id'),
            'item_id'        => $request->input('item_id'),
            'payment_method' => $request->input('payment_method'),
            'reset_search'   => $request->input('reset_search'),
            'per_page'       => (int)$request->input('per_page', 25),
        ];
        $perPage = $filters['per_page'] ?: 25;

        // Base query
        $q = UniformRequest::query()
            ->with(['player.user', 'item', 'branch', 'system', 'currency']);

        // ---------- Role scoping ----------
        switch ($user->role) {
            case 'full_admin':
                // no scoping
                break;

            case 'system_admin':
                if ($user->system_id && Schema::hasColumn((new UniformRequest())->getTable(), 'system_id')) {
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
                    // use academy_id if exists; otherwise scope via player's academy below in the filters
                    if (Schema::hasColumn((new UniformRequest())->getTable(), 'academy_id')) {
                        $q->whereIn('academy_id', $academyIds);
                    } else {
                        $q->whereHas('player', fn ($p) => $p->whereIn('academy_id', $academyIds));
                    }
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

        // ---------- Filters ----------
        $dateColumn = Schema::hasColumn((new UniformRequest())->getTable(), 'request_date')
            ? 'request_date'
            : 'created_at';

        if ($filters['date_from']) $q->whereDate($dateColumn, '>=', $filters['date_from']);
        if ($filters['date_to'])   $q->whereDate($dateColumn, '<=', $filters['date_to']);

        $q->when($filters['status'],         fn($qq) => $qq->where('status', $filters['status']))
          ->when($filters['branch_status'],  fn($qq) => $qq->where('branch_status', $filters['branch_status']))
          ->when($filters['office_status'],  fn($qq) => $qq->where('office_status', $filters['office_status']))
          ->when($filters['branch_id'],      fn($qq) => $qq->where('branch_id', $filters['branch_id']))
          ->when($filters['academy_id'],     function ($qq) use ($filters) {
                if (Schema::hasColumn((new UniformRequest())->getTable(), 'academy_id')) {
                    $qq->where('academy_id', $filters['academy_id']);
                } else {
                    $qq->whereHas('player', fn ($p) => $p->where('academy_id', $filters['academy_id']));
                }
          })
          ->when($filters['player_id'],      fn($qq) => $qq->where('player_id', $filters['player_id']))
          ->when($filters['item_id'],        fn($qq) => $qq->where('item_id', $filters['item_id']))
          ->when($filters['payment_method'], fn($qq) => $qq->where('payment_method', 'like', '%'.$filters['payment_method'].'%'))
          ->when($filters['reset_search'],   fn($qq) => $qq->where('admin_remarks', 'like', '%'.$filters['reset_search'].'%'));

        // ---------- Aggregates ----------
        $totals = (clone $q)->selectRaw('
            COALESCE(SUM(amount),0)      as amount_sum,
            COALESCE(SUM(quantity),0)    as qty_sum,
            COUNT(*)                     as rows_count
        ')->first();

        $mainStatusCounts   = (clone $q)->selectRaw('status, COUNT(*) c')->groupBy('status')->pluck('c','status')->toArray();
        $branchStatusCounts = (clone $q)->selectRaw('branch_status, COUNT(*) c')->groupBy('branch_status')->pluck('c','branch_status')->toArray();
        $officeStatusCounts = (clone $q)->selectRaw('office_status, COUNT(*) c')->groupBy('office_status')->pluck('c','office_status')->toArray();

        // ---------- Export CSV ----------
        if ($request->input('export') === 'csv') {
            $filename = 'uniforms_report_'.now()->format('Ymd_His').'.csv';
            $stream = function () use ($q) {
                $h = fopen('php://output', 'w');
                fputcsv($h, [
                    'ID','Request Date','Status','Branch Status','Office Status',
                    'Player','Item','Branch','Size','Color','Qty','Amount','Currency','Payment Method'
                ]);
                (clone $q)->orderBy('id')->chunk(1000, function($rows) use ($h) {
                    foreach ($rows as $r) {
                        fputcsv($h, [
                            $r->id,
                            optional($r->request_date)->format('Y-m-d'),
                            $r->status,
                            $r->branch_status,
                            $r->office_status,
                            optional($r->player?->user)->name ?? '',
                            optional($r->item)->name_en ?? optional($r->item)->name ?? '',
                            // Branch model has getTranslatedNameAttribute(), falls back to name
                            optional($r->branch)->translated_name ?? optional($r->branch)->name ?? '',
                            $r->size,
                            $r->color,
                            (int)$r->quantity,
                            number_format((float)$r->amount, 2, '.', ''),
                            optional($r->currency)->code ?? '',
                            $r->payment_method ?? '',
                        ]);
                    }
                });
                fclose($h);
            };
            return response()->streamDownload($stream, $filename, ['Content-Type' => 'text/csv']);
        }

        // ---------- Pagination ----------
        $uniforms = $q->latest()->paginate($perPage)->appends($request->query());

        // ---------- Dropdown options ----------
        [$branchOptions, $academyOptions] = $this->buildScopedOptions($user, $academyIds, $filters['branch_id']);

        $itemOptions = Item::orderBy('name_en')->pluck('name_en','id');

        // Players: order by users.name (players table has no name)
        $playerOptions = $this->buildPlayerOptions($user, $academyIds, $filters);

        // AJAX partial only (table + totals)
        if ($request->ajax()) {
            return view('admin.reports.uniforms.partials.results', compact(
                'uniforms','totals','mainStatusCounts','branchStatusCounts','officeStatusCounts'
            ));
        }

        // Full page
        return view('admin.reports.uniforms.index', compact(
            'uniforms',
            'totals',
            'mainStatusCounts',
            'branchStatusCounts',
            'officeStatusCounts',
            'filters',
            'branchOptions',
            'academyOptions',
            'itemOptions',
            'playerOptions'
        ));
    }

    /**
     * AJAX: academies by branch (POST), scoped by user system/role.
     */
    public function academiesForBranch(UniformReportRequest $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $branchId = (int)$request->input('branch_id');
        if (!$branchId) {
            return response()->json(['data' => []]);
        }

        $user = auth()->user();
        $branch = Branch::find($branchId);
        if (!$branch) {
            return response()->json(['data' => []]);
        }

        // Basic permission checks
        if ($user->role === 'system_admin' && $user->system_id && $branch->system_id != $user->system_id) {
            return response()->json(['data' => []]);
        }
        if (in_array($user->role, ['branch_admin','coach']) && $user->branch_id && $user->branch_id != $branchId) {
            return response()->json(['data' => []]);
        }

        $list = Academy::where('branch_id', $branchId)->orderBy('name_en')->get(['id','name_en']);
        return response()->json([
            'data' => $list->map(fn($a) => ['id' => $a->id, 'name' => $a->name_en])->values(),
        ]);
    }

    /**
     * Build Branch/Academy dropdown options based on user role and current branch filter.
     */
    private function buildScopedOptions($user, array $academyIds, $selectedBranchId = null): array
    {
        $branchOptions  = collect();
        $academyOptions = collect();

        switch ($user->role) {
            case 'full_admin':
                $branchOptions  = Branch::orderBy('name')->pluck('name','id'); // Branch has `name`
                $academyOptions = $selectedBranchId
                    ? Academy::where('branch_id', $selectedBranchId)->orderBy('name_en')->pluck('name_en','id')
                    : collect();
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $branchOptions = Branch::where('system_id', $user->system_id)
                        ->orderBy('name')->pluck('name','id');
                    $academyOptions = $selectedBranchId
                        ? Academy::where('branch_id', $selectedBranchId)->orderBy('name_en')->pluck('name_en','id')
                        : collect();
                }
                break;

            case 'branch_admin':
            case 'coach':
                if ($user->branch_id) {
                    $branchOptions  = Branch::where('id', $user->branch_id)->orderBy('name')->pluck('name','id');
                    $academyOptions = Academy::where('branch_id', $user->branch_id)->orderBy('name_en')->pluck('name_en','id');
                }
                break;

            case 'academy_admin':
                if (!empty($academyIds)) {
                    $branchIds = Academy::whereIn('id', $academyIds)->pluck('branch_id')->unique();
                    $branchOptions = Branch::whereIn('id', $branchIds)->orderBy('name')->pluck('name','id');
                    $academyOptions = $selectedBranchId
                        ? Academy::where('branch_id', $selectedBranchId)->whereIn('id', $academyIds)->orderBy('name_en')->pluck('name_en','id')
                        : Academy::whereIn('id', $academyIds)->orderBy('name_en')->pluck('name_en','id');
                }
                break;

            case 'player':
                // no global lists for players
                break;
        }

        return [$branchOptions, $academyOptions];
    }

    /**
     * Build player dropdown [id => users.name], ordered by users.name, and scoped by role/filters.
     */
    private function buildPlayerOptions($user, array $academyIds, array $filters)
    {
        $po = Player::select('players.id', 'users.name')
            ->join('users', 'users.id', '=', 'players.user_id');

        // Role scoping
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id && Schema::hasColumn('players', 'branch_id')) {
                    // If you need strict system scoping, derive branch/academy sets from system
                    $po->whereIn('players.id', function ($sub) use ($user) {
                        $sub->from('players')
                            ->select('players.id')
                            ->join('branches', 'branches.id', '=', 'players.branch_id')
                            ->where('branches.system_id', $user->system_id);
                    });
                }
                break;

            case 'branch_admin':
            case 'coach':
                if ($user->branch_id) {
                    $po->where('players.branch_id', $user->branch_id);
                } else {
                    $po->whereRaw('1=0');
                }
                break;

            case 'academy_admin':
                if (!empty($academyIds)) {
                    $po->whereIn('players.academy_id', $academyIds);
                } else {
                    $po->whereRaw('1=0');
                }
                break;

            case 'player':
                $po->where('players.user_id', $user->id);
                break;
        }

        // Filter scoping (optional: reflect currently chosen branch/academy in the dropdown)
        if (!empty($filters['branch_id'])) {
            $po->where('players.branch_id', $filters['branch_id']);
        }
        if (!empty($filters['academy_id'])) {
            $po->where('players.academy_id', $filters['academy_id']);
        }

        return $po->orderBy('users.name', 'asc')
                  ->get()
                  ->pluck('name', 'id');
    }
}
