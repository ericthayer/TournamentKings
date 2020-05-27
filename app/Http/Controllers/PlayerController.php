<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdatePlayerRequest;
use App\Tournamentkings\Entities\Models\Player;

class PlayerController extends Controller
{
    public function show(Request $request, Player $player)
    {
        $loadedPlayer = Player::with(['tournaments.matches' => function ($query) use ($player) {
            $query->whereHas('players', function ($query) use ($player) {
                $query->where('player_id', $player->id);
            });
        }, 'tournaments.gameType'])->with('user')->find($player->id);

        if ($request->ajax()) {
            return $loadedPlayer->toJson();
        }

        return view('players.details')->with('player', $loadedPlayer);
    }

    public function update(UpdatePlayerRequest $request, Player $player)
    {
        $player->gamer_tag = $request->input('gamer_tag');
        $player->save();
    }
}
