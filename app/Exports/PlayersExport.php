<?php

namespace App\Exports;

use App\Models\Player;
use App\Models\Branch;
use App\Models\Academy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PlayersExport implements FromView
{
    public function view(): View
    {
        $user = Auth::user();

        $query = Player::with(['user', 'sport', 'branch', 'academy', 'nationality', 'payments']);

        // Apply role-based restrictions
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id')->toArray();
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id')->toArray();
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'academy_admin':
            case 'coach':
            case 'player':
                $academyIds = is_array($user->academy_id)
                    ? $user->academy_id
                    : json_decode($user->academy_id, true) ?? [];

                if (!empty($academyIds)) {
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            default:
                // full_admin or unknown role: no restrictions
                break;
        }

        $players = $query->get();

        return view('admin.exports.players', [
            'players' => $players
        ]);
    }
}
