<?php
namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AttendanceExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $user = Auth::user();
        $request = $this->request;

        $query = Attendance::with(['user', 'player', 'branch']);

        switch ($user->role) {
            case 'full_admin':
            case 'system_admin':
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

        // ✅ Apply filters from request
        if ($request->filled('start_date')) {
            $query->whereDate('scanned_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('scanned_at', '<=', $request->end_date);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn($q) => $q->where('role', $request->role));
        }

        $attendances = $query->latest()->get();

        return view('admin.attendance.export', [
            'attendances' => $attendances
        ]);
    }
}
