<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Card;

class CardController extends Controller
{
    public function scan($player_id)
    {
        $player = Player::with('user', 'card')->findOrFail($player_id);
        $serial_number = optional($player->card)->serial_number; // nullable


        return view('admin.cards.scan', compact('player', 'serial_number'));
    }

public function store(Request $request)
{
    $request->validate([
        'player_id' => 'required|exists:players,id',
        'serial_number' => 'required|unique:cards,serial_number',
    ]);

    $player = Player::with('user')->findOrFail($request->player_id);

    // Check if user already has a card
    $existing = \App\Models\Card::where('user_id', $player->user_id)->first();
    if ($existing) {
        return redirect()->back()->withErrors(['serial_number' => __('card.card_already_exists')]);
    }

    // Create the card
    Card::create([
        'player_id' => $player->id,
        'user_id' => $player->user_id,
        'serial_number' => $request->serial_number,
    ]);

    // ✅ Update user's card_serial_number
    $player->user->update([
        'card_serial_number' => $request->serial_number,
    ]);

    return redirect()->route('admin.players.index')->with('success', __('card.card_assigned_successfully'));
}


}
