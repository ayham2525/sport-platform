<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Player;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'card_serial_number' => 'required|string',
        ]);

        $serial = $request->card_serial_number;

        $user = User::where('card_serial_number', $serial)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $player = $user->role === 'player'
            ? Player::where('user_id', $user->id)->first()
            : null;

        Attendance::create([
            'user_id'   => $user->id,
            'player_id' => $player?->id,
            'branch_id'    => $user->branch_id ?? null,
            'scanned_at'=> now(),
        ]);

        return response()->json([
            'status'     => 'ok',
            'user'       => $user->name,
            'role'       => $user->role,
            'branch_id'  => $user->branch_id,
        ]);
    }
}
