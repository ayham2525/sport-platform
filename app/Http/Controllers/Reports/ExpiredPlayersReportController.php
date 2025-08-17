<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpiredPlayersReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        // Default to today if not provided
        $start = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::today()->startOfDay();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::today()->endOfDay();

        $expiredPlayers = Player::query()
            ->whereDoesntHave('payments', function ($q) use ($end) {
                $q->where('end_date', '>=', $end); // exclude active players
            })
            ->whereHas('payments', function ($q) use ($start, $end) {
                $q->whereBetween('end_date', [$start, $end]);
            })
            ->with([
                'user',
                'branch',
                'academy',
                'nationality',
                'sport',
            ])
            ->paginate(20);

        return view('admin.reports.expired_players.index', [
            'expiredPlayers' => $expiredPlayers,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
        ]);
    }
}
