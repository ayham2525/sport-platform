<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Player;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Carbon\Carbon;

class AttendanceController extends Controller
{
 public function index(Request $request)
{
    $user = auth()->user();
    $query = Attendance::with(['user', 'player', 'branch']);

    switch ($user->role) {
        case 'full_admin':
            $query->whereHas('user', fn($q) =>
                $user->system_id ? $q->where('system_id', $user->system_id) : $q
            );
            break;
        case 'branch_admin':
            $query->whereHas('user', fn($q) =>
                $user->branch_id ? $q->where('branch_id', $user->branch_id) : $q
            );
            break;
        case 'academy_admin':
            $academyIds = is_array($user->academy_id)
                ? $user->academy_id
                : json_decode($user->academy_id, true);

            $query->whereHas('player', fn($q) =>
                $academyIds ? $q->whereIn('academy_id', $academyIds) : $q
            );
            break;
        case 'player':
            $query->where('user_id', $user->id);
            break;
        default:
            abort(403);
    }

    // 🔹 Set default start and end date to current month
    $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
    $endDate = $request->end_date ?? now()->endOfMonth()->toDateString();

    // 🔹 Apply date filters
    $query->when($request->filled('start_date'), fn($q) =>
        $q->whereDate('scanned_at', '>=', $request->start_date)
    );
    $query->when($request->filled('end_date'), fn($q) =>
        $q->whereDate('scanned_at', '<=', $request->end_date)
    );

    // 🔹 Filter by role
    if ($request->filled('role')) {
        $query->whereHas('user', fn($q) =>
            $q->where('role', $request->role)
        );
    }

    $attendances = $query->latest()->paginate(20);

    return view('admin.attendance.index', compact('attendances', 'startDate', 'endDate'));
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
}
