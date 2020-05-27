<?php

namespace App\TournamentKings\Managers;

use App\Jobs\ProcessTransaction;
use Illuminate\Support\MessageBag;
use App\Tournamentkings\Entities\Models\User;
use App\Tournamentkings\Entities\Models\Placement;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Entities\Models\UserWithdrawal;
use App\TournamentKings\Entities\Models\TransactionType;
use App\TournamentKings\Payment\Transaction as PaymentTransaction;

class BalanceManager
{
    public static function setupToTournamentTransaction(Tournament $tournament, array $validatedData): PaymentTransaction
    {
        $amount    = $validatedData['amount'];

        $transaction                         = new PaymentTransaction;
        $transaction->transactableModelClass = Tournament::class;
        $transaction->transactableModelId    = $tournament->id;

        $transaction->amount           = floatval($amount);
        $transaction->transactionType  = TransactionType::TOURNAMENT_BUY_IN;
        $transaction->userId           = auth()->user()->id;

        return $transaction;
    }

    public static function completeTransactionToTournament(Tournament $tournament, array $validatedData): MessageBag
    {
        $transaction = self::setupToTournamentTransaction($tournament, $validatedData);

        return self::makeTransaction($transaction);
    }

    public static function setupTournamentPayout(Placement $placement): PaymentTransaction
    {
        $tournament        = $placement->playerTournament->tournament;
        $player            = $placement->playerTournament->player;
        $placementTypeName = $placement->placementType->name;

        $transaction = new PaymentTransaction();

        $prizeData = $tournament
            ->prizes
            ->where('placement_type_name', $placementTypeName)
            ->first();

        $transaction->amount                 = $prizeData->amount;
        $transaction->transactionType        = TransactionType::TOURNAMENT_PAYOUT;
        $transaction->transactionId          = $tournament->id;
        $transaction->transactableModelId    = $player->user->id;
        $transaction->transactableModelClass = User::class;

        return $transaction;
    }

    /**
     * Complete a transaction from tournament to user.
     *
     * @param Placement $placement
     * @return MessageBag
     */
    public static function completeTournamentPayout(Placement $placement): MessageBag
    {
        $transaction = self::setupTournamentPayout($placement);

        return self::makeTransaction($transaction);
    }

    /**
     * Complete a negative transaction on the user's balance.
     *
     * @param array $validatedData
     * @return MessageBag
     */
    public static function completeUserWithdrawal(array $validatedData): MessageBag
    {
        $transaction = self::setupUserWithdrawal($validatedData);

        return self::makeTransaction($transaction);
    }

    public static function setupUserWithdrawal(array $validatedData): PaymentTransaction
    {
        $amount = $validatedData['amount'];

        $transaction = new PaymentTransaction();

        $transaction->amount                 = $amount;
        $transaction->transactionType        = TransactionType::USER_WITHDRAWAL;
        $transaction->transactableModelId    = auth()->user()->id;
        $transaction->transactableModelClass = User::class;

        $data                  = $validatedData;
        $transaction->data     = $data;

        return $transaction;
    }

    /**
     * Complete a positive transaction on the user's balance.
     *
     * @param array $validatedData
     * @return MessageBag
     */
    public static function completeUserRefund(UserWithdrawal $userWithdrawal): MessageBag
    {
        $transaction = self::setupUserRefund($userWithdrawal);

        return self::makeTransaction($transaction);
    }

    public static function setupUserRefund(UserWithdrawal $userWithdrawal): PaymentTransaction
    {
        $amount = $userWithdrawal->amount;

        $transaction = new PaymentTransaction();

        $transaction->amount                 = $amount;
        $transaction->transactionId          = $userWithdrawal->id;
        $transaction->transactionType        = TransactionType::USER_REFUND;
        $transaction->transactableModelId    = $userWithdrawal->user_id;
        $transaction->transactableModelClass = User::class;

        return $transaction;
    }

    public static function setupTkPayout(Tournament $tournament): PaymentTransaction
    {
        $transaction = new PaymentTransaction();

        $tournamentBalance = $tournament->balance->balance;

        $transaction->amount                 = $tournamentBalance;
        $transaction->transactionType        = TransactionType::TK_PAYOUT;
        $transaction->transactableModelId    = $tournament->id;
        $transaction->transactableModelClass = Tournament::class;

        return $transaction;
    }

    /**
     * Complete a transaction from tournament to user.
     *
     * @param Placement $placement
     * @return MessageBag
     */
    public static function completeTkPayout(Tournament $tournament): MessageBag
    {
        $transaction = self::setupTkPayout($tournament);

        return self::makeTransaction($transaction);
    }

    public static function makeTransaction(PaymentTransaction $transaction): MessageBag
    {
        $errors = new MessageBag;

        switch ($transaction->transactionType) {
            case TransactionType::TOURNAMENT_BUY_IN:
                $balanceData = auth()->user()->balance;

                if (! $balanceData) {
                    $errors->add('no-balance', __('balance.no-balance'));
                    break;
                }

                $balance               = $balanceData->balance;
                $diff                  = bcsub($balance, $transaction->amount);
                $balanceIsInsufficient = bccomp($diff, 0) === -1;
                if ($balanceIsInsufficient) {
                    $errors->add('insufficient-funds', __('balance.no-balance'));
                    break;
                }
                break;
            case 'square':

                break;
        }

        if ($errors->isEmpty()) {
            dispatch(new ProcessTransaction($transaction));
        }

        return $errors;
    }
}
