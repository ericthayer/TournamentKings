<?php

namespace App\Listeners;

use App\Events\WonMatch;
use App\TournamentKings\Managers\BalanceManager;

class AwardPlayers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WonMatch  $event
     * @return void
     */
    public function handle(WonMatch $event)
    {
        if (! $event->placement->playerTournament->tournament->entryFeeType->is_free) {
            BalanceManager::completeTournamentPayout($event->placement);
        }
    }
}
