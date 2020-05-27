<?php

namespace App\Listeners;

use App\Events\TournamentDeleted;
use App\TournamentKings\Managers\BalanceManager;
use App\Tournamentkings\Entities\Models\UserWithdrawal;
use App\Tournamentkings\Entities\Models\TransactionType;

class RefundUser
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
     * @param  TournamentDeleted  $event
     * @return void
     */
    public function handle(TournamentDeleted $event)
    {
        $players    = $event->tournament->players;
        $tournament = $event->tournament;

        // Only issue refunds for incomplete tournaments
        if ($tournament->winner_player_id) {
            return;
        }

        $players->each(function ($player) use ($tournament) {
            $tournamentId = $tournament->id;
            $user = $player->user;
            $refundSum = abs($user->transactions
                ->where('transaction_type_name', TransactionType::TOURNAMENT_BUY_IN)
                ->where('transaction_id', $tournamentId)->sum('amount')
            );
            $userWithdrawal = UserWithdrawal::create([
                'user_id'                => $user->id,
                'amount'                 => $refundSum,
                'withdrawal_type_name'   => 'paypal',
            ]);

            BalanceManager::completeUserRefund($userWithdrawal);
        });
    }
}
