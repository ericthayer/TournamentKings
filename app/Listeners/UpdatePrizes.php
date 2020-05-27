<?php

namespace App\Listeners;

use App\Events\Tournament\BuyIn;
use App\Tournamentkings\Entities\Models\TkSetting;
use App\Tournamentkings\Entities\Models\PlacementType;
use App\Tournamentkings\Entities\Models\TournamentPrizes;
use App\Tournamentkings\Entities\Models\TournamentBalance;

class UpdatePrizes
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
     * @param  BuyIn  $event
     * @return void
     */
    public function handle(BuyIn $event)
    {
        $tournament_id = $event->tournament->id;
        $prizeNameList = PlacementType::prizeNameList();

        if ($event->tournament->total_slots === 2) {
            $prizeNameList = ['gold'];
        }

        $updatedPrizes = collect($prizeNameList)->mapWithKeys(function ($name) use ($tournament_id, $event) {
            $tournamentBalance = TournamentBalance::find($tournament_id)->balance;
            $feeDecimal = TkSetting::find(1)->split;
            $feeFactor = bcsub(1, $feeDecimal, 4);
            $balanceAfterFee = bcmul($tournamentBalance, $feeFactor, 2);

            $prizeAmount = $balanceAfterFee;
            if ($event->tournament->total_slots !== 2) {
                $placementSplit = PlacementType::$name()->split;
                $prizeAmount = bcmul($placementSplit, $balanceAfterFee, 2);
            }

            return [
                $name => $prizeAmount,
            ];
        });

        $updatedPrizes->each(function ($amount, $placement_type_name) use ($tournament_id) {
            TournamentPrizes::updateOrCreate([
                'tournament_id'       => $tournament_id,
                'placement_type_name' => $placement_type_name,
            ], compact('amount'));
        });
    }
}
