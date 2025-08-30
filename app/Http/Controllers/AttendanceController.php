<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Player;
use App\Models\Attendance;
use App\Models\System;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;


class AttendanceController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    $query = Attendance::with([
        'user',
        'player',
        'branch' => fn($q) => $q->withTrashed(),
    ]);

    // ---------- Role scoping (unchanged) ----------
    switch ($user->role) {
        case 'full_admin':
            break;
        case 'system_admin':
            if ($user->system_id) {
                $query->whereHas('user', fn($q) => $q->where('system_id', $user->system_id));
            }
            break;
        case 'branch_admin':
            if ($user->branch_id) {
                $query->whereHas('user', fn($q) => $q->where('branch_id', $user->branch_id));
            }
            break;
        case 'academy_admin':
            $academyIds = $user->academy_id;
            if (is_string($academyIds)) {
                $decoded = json_decode($academyIds, true);
                $academyIds = is_array($decoded) ? $decoded : (strlen($academyIds) ? [$academyIds] : []);
            } elseif (is_int($academyIds)) {
                $academyIds = [$academyIds];
            } elseif (!is_array($academyIds)) {
                $academyIds = [];
            }
            if (!empty($academyIds)) {
                $query->whereHas('player', fn($q) => $q->whereIn('academy_id', $academyIds));
            }
            break;
        case 'player':
            $query->where('user_id', $user->id);
            break;
        default:
            abort(403);
    }

    // ---------- Date range (unchanged) ----------
    $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
    $endDate   = $request->input('end_date',   now()->endOfMonth()->toDateString());
    $query->whereDate('scanned_at', '>=', $startDate)
          ->whereDate('scanned_at', '<=', $endDate);

    // ---------- Filters: role (unchanged) ----------
    if ($request->filled('role')) {
        $query->whereHas('user', fn($q) => $q->where('role', $request->role));
    }

    // ---------- NEW: Filters: system_id + branch_id ----------
    $systemId = $request->input('system_id');
    $branchId = $request->input('branch_id');

    if ($systemId) {
        // Filter via the related branch's system_id
        $query->whereHas('branch', fn($b) => $b->withTrashed()->where('system_id', $systemId));
    }

    if ($branchId) {
        $query->where('branch_id', $branchId);
    }

    $attendances = $query->latest()->paginate(20);

    // ---------- Build filter dropdown data ----------
    // Systems list (respect role)
    $systemsQ = System::query()->orderBy('name');
    if ($user->role === 'system_admin' && $user->system_id) {
        $systemsQ->where('id', $user->system_id);
    } elseif (!in_array($user->role, ['full_admin','system_admin'])) {
        // For non-admin roles, keep the system dropdown single/readonly if they have one
        if ($user->system_id) $systemsQ->where('id', $user->system_id);
    }
    $systems = $systemsQ->get();

    // Branches list depends on selected system (and role)
    $branchesQ = Branch::query()->orderBy('name');
    if ($systemId) {
        $branchesQ->where('system_id', $systemId);
    } elseif ($user->system_id) {
        $branchesQ->where('system_id', $user->system_id);
    }
    if ($user->role === 'branch_admin' && $user->branch_id) {
        $branchesQ->where('id', $user->branch_id);
    }
    $branches = $branchesQ->get();

    return view('admin.attendance.index', compact(
        'attendances', 'startDate', 'endDate', 'systems', 'branches', 'systemId', 'branchId'
    ));
}




    public function create()
    {
        $user = auth()->user();

        // Base query for users (players, coaches, etc.)
        $userQuery = User::query();
        $branchQuery = Branch::query();

        switch ($user->role) {
            case 'full_admin':
                // Return all branches without filtering (full_admin can see everything)
                $userQuery->where('system_id', $user->system_id);
                // Do not apply any restrictions on branches for full_admin
                break;

            case 'system_admin':
                $userQuery->where('system_id', $user->system_id);
                $branchQuery->where('system_id', $user->system_id);
                break;

            case 'branch_admin':
                $userQuery->where('branch_id', $user->branch_id);
                $branchQuery->where('id', $user->branch_id);
                break;

            case 'academy_admin':
                $academyIds = is_array($user->academy_id)
                    ? $user->academy_id
                    : json_decode($user->academy_id, true);

                $userQuery->where(function ($q) use ($academyIds) {
                    $q->whereIn('academy_id', $academyIds);
                });

                $branchQuery->whereIn('id', [$user->branch_id]);
                break;

            case 'coach':
            case 'player':
                $userQuery->where('id', $user->id);
                $branchQuery->where('id', $user->branch_id);
                break;

            default:
                abort(403);
        }


        $users = $userQuery->orderBy('name')->get();
        $branches = $branchQuery->orderBy('name')->get();

        return view('admin.attendance.create', compact('branches', 'users'));
    }


    public function store(Request $request)
    {
        // 🔹 Validate inputs
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'branch_id'   => 'required|exists:branches,id',
            'scanned_at'  => 'required|date',
        ]);

        // 🔹 Get selected user
        $user = User::findOrFail($request->user_id);

        // 🔹 Get player if user is a player
        $player = null;
        if ($user->role === 'player') {
            $player = Player::where('user_id', $user->id)->first();

            if (! $player) {
                return back()->withErrors([
                    'user_id' => __('attendance.no_player_profile_found')
                ])->withInput();
            }
        }

        // 🔹 Create Attendance record
        Attendance::create([
            'user_id'    => $user->id,
            'player_id'  => $player?->id,
            'branch_id'  => $request->branch_id,
            'scanned_at' => $request->scanned_at,
        ]);

        return redirect()
            ->route('admin.attendance.index')
            ->with('success', __('attendance.created'));
    }


    public function edit($id)
    {
        $attendance = Attendance::with('branch')->findOrFail($id);
        return view('admin.attendance.edit', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->only(['scanned_at', 'branch_at']));
        return redirect()->route('admin.attendance.index')->with('success', __('attendance.updated'));
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('admin.attendance.index')->with('success', __('attendance.deleted'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new AttendanceExport($request),
            'attendance.xlsx'
        );
    }
    public function scan()
    {
        // page to take attendance by scanning a card
        return view('admin.attendance.scan');
    }

  public function scanStore(Request $request)
{
    $request->validate([
        'card_serial_number' => 'required|string|max:50',
    ]);

    $user = User::where('card_serial_number', $request->card_serial_number)->first();
    if (!$user) {
        return response()->json(['ok' => false, 'message' => __('attendance.messages.not_found')], 404);
    }

    $playerId = null;
    $player   = null;
    if ($user->role === 'player') {
        $playerId = Player::where('user_id', $user->id)->value('id');
        $player   = $playerId ? Player::with('branch')->find($playerId) : null;
    }

    // Save attendance (keep your varchar branch_id if your schema requires it)
    Attendance::create([
        'user_id'    => $user->id,
        'player_id'  => $playerId,
        'branch_id'  => (string)($user->branch_id ?? ''), // if your column is string
        'scanned_at' => now(),
    ]);

    // Build last 10 records table
    $records = Attendance::with(['user', 'player', 'branch' => fn($b) => $b->withTrashed()])
        ->where('user_id', $user->id)
        ->orderByDesc('scanned_at')
        ->paginate(10);

    $html = View::make('admin.attendance._table', compact('records'))->render();

    // ---- NEW: compute subscription window + remaining classes for this player ----
    $summaryHtml = '';
    $summaryHtml = '';
if ($player) {
    $info = $player->latestProgramProgress();

    $startStr = $info['start'] ? $info['start']->toDateString() : '-';
    $prog     = e($info['program_name'] ?? '-');

    // We’re not using end_date/phase anymore; keep a neutral badge
    $badge = '<span class="badge badge-info">'.__('attendance.progress') ?? 'Progress'.'</span>';

    $summaryHtml = '
    <div class="alert alert-secondary" role="alert">
        <div class="d-flex flex-wrap align-items-center">
            <div class="mr-3"><strong>'.__('player.fields.name').':</strong> '.e(optional($player->user)->name ?? '-').'</div>
            <div class="mr-3"><strong>'.__('attendance.fields.branch').':</strong> '.e(optional($player->branch)->name ?? ($player->branch_id ?? '-')).'</div>
            <div class="mr-3"><strong>'.__('program.program').':</strong> '.$prog.'</div>
            <div class="mr-3"><strong>'.__('attendance.fields.attended').':</strong> '.$info['attended'].' / '.$info['total_classes'].'</div>
            <div class="mr-3"><strong>'.__('attendance.fields.remaining_classes').':</strong> '.$info['remaining'].'</div>
            <div class="mr-3"><small class="text-muted">'.__('attendance.fields.date_from') .': '.$startStr.'</small></div>
            <div class="ml-auto">'.$badge.'</div>
        </div>
    </div>';
}else {
        // Not a player; show a minimal notice (optional)
        $summaryHtml = '
        <div class="alert alert-light" role="alert">
            '. __('attendance.messages.saved') .'
        </div>';
    }

    return response()->json([
        'ok'           => true,
        'message'      => __('attendance.messages.saved'),
        'html'         => $html,
        'summary_html' => $summaryHtml,
    ]);
}


    /**
     * AJAX search with POST (date range + card serial) + pagination
     * expects: date_from, date_to, card_serial_number, page, per_page
     */
    public function search(Request $request)
{
    $auth = auth()->user();

    $q = Attendance::with(['user', 'player', 'branch' => fn($b) => $b->withTrashed()]);

    // ---------- Role scoping (same as index) ----------
    switch ($auth->role) {
        case 'full_admin':
            break;
        case 'system_admin':
            if ($auth->system_id) {
                $q->whereHas('user', fn($u) => $u->where('system_id', $auth->system_id));
            }
            break;
        case 'branch_admin':
            if ($auth->branch_id) {
                $q->whereHas('user', fn($u) => $u->where('branch_id', $auth->branch_id));
            }
            break;
        case 'academy_admin':
            $academyIds = $auth->academy_id;
            if (is_string($academyIds)) $academyIds = json_decode($academyIds, true) ?: [];
            if (is_int($academyIds))   $academyIds = [$academyIds];
            if (!is_array($academyIds)) $academyIds = [];
            if ($academyIds) {
                $q->whereHas('player', fn($p) => $p->whereIn('academy_id', $academyIds));
            }
            break;
        case 'player':
            $q->where('user_id', $auth->id);
            break;
        default:
            return response()->json(['ok' => false], 403);
    }

    // ---------- NEW: Filters: system_id + branch_id ----------
    if ($request->filled('system_id')) {
        $q->whereHas('branch', fn($b) => $b->withTrashed()->where('system_id', $request->system_id));
    }
    if ($request->filled('branch_id')) {
        $q->where('branch_id', $request->branch_id);
    }

    // Card serial filter
    if ($request->filled('card_serial_number')) {
        $serial = trim($request->card_serial_number);
        $q->whereHas('user', fn($u) => $u->where('card_serial_number', $serial));
    }

    // Date filters
    if ($request->filled('date_from')) {
        $q->whereDate('scanned_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $q->whereDate('scanned_at', '<=', $request->date_to);
    }

    $q->orderByDesc('scanned_at');

    $perPage = (int)($request->input('per_page', 20));
    $page    = (int)($request->input('page', 1));
    $records = $q->paginate($perPage, ['*'], 'page', $page);

    $html = \View::make('admin.attendance._table', compact('records'))->render();

    return response()->json([
        'ok'       => true,
        'html'     => $html,
        'paginate' => [
            'current_page' => $records->currentPage(),
            'last_page'    => $records->lastPage(),
        ]
    ]);
}

}
