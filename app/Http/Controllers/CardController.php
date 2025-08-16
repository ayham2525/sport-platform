<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Card;
use Illuminate\Validation\Rule;

class CardController extends Controller
{
    public function scan($player_id)
    {
        $player = Player::with('user', 'card')->findOrFail($player_id);
        $card_serial_number = $player->user->card_serial_number;




        return view('admin.cards.scan', compact('player', 'card_serial_number'));
    }



public function store(Request $request)
{
    $player = Player::with('user')->findOrFail($request->player_id);

    $request->validate([
        'player_id' => 'required|exists:players,id',
        'card_serial_number' => [
            'required',
            Rule::unique('users', 'card_serial_number')->ignore($player->user_id),
        ],
    ]);

    // Update only the user's card_serial_number
    $player->user->update([
        'card_serial_number' => $request->card_serial_number,
    ]);

    return redirect()
        ->route('admin.players.index')
        ->with('success', __('card.card_assigned_successfully'));
}



}
