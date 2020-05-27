<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\MessageBag;
use App\Tournamentkings\Entities\Models\Tournament;
use App\Tournamentkings\Entities\Models\EntryFeeType;

class CanRegisterForTournament
{
    const ENTRY_FEE_INT_VAL = 100;

    /**
     * If the entry fee type requires it, then the user
     * must add a certain amount of payment to the tournament
     * before registering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Tournament $tournament */
        $tournament   = $request->tournament;
        $entryFeeType = $tournament->entryFeeType->name;

        $errors = new MessageBag;
        switch ($entryFeeType) {
            case EntryFeeType::FLAT_FEE:
                $errors = $this->hasPaid($tournament, $tournament->entry_fee);
            break;
            case EntryFeeType::TARGET_POT:
                $errors = $this->hasPaid($tournament, $tournament->player_deposit);
            break;
        }

        if ($errors->isNotEmpty()) {
            return back()->withErrors($errors)->with('amount', $request->amount);
        }

        return $next($request);
    }

    /**
     * Determine if the authorized user has enough tournament buy-in transactions
     * to satisfy the fee.
     *
     * @param Tournament $tournament
     * @param int $fee
     * @return MessageBag
     */
    private function hasPaid(Tournament $tournament, float $fee): MessageBag
    {
        setlocale(LC_MONETARY, 'en_US');

        $balance = auth()->user()->balance ? auth()->user()->balance->balance : 0;

        $errors            = new MessageBag;
        $insufficientFunds = bccomp($balance, $fee) === -1;
        if ($insufficientFunds) {
            $diff          = bcsub($fee, $balance, 2);
            $formattedDiff = money_format('%+.2n', $diff);
            $errors->add('entry-fee', __('tournaments.entry-fee-error', ['fee' => $formattedDiff]));
        }

        return $errors;
    }
}
