<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\UserWithdrawal;
use App\Events\Tournament\BuyIn;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Managers\BalanceManager;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Entities\Models\Transaction;
use App\TournamentKings\Entities\Models\TransactionType;
use App\TournamentKings\Payment\Transaction as PaymentTransaction;
use App\TournamentKings\Entities\Models\UserWithdrawal as UserWithdrawalModel;

class ProcessTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PaymentTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * Make sure that what is passed in is either scalar data or very simple
     * and serializable objects. Otherwise, you will have trouble.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $transactionType = $this->transaction->transactionType;
            $transactableModelClass = $this->transaction->transactableModelClass;

            switch ($transactionType) {
                case TransactionType::SQUARE:
                    // A user is adding funds to their account
                    $transaction = new Transaction([
                        'transaction_id' => $this->transaction->transactionId,
                        'amount'         => $this->transaction->amount,
                    ]);

                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;

                case TransactionType::TOURNAMENT_BUY_IN:
                    // A user is transferring funds from their account to a tournament
                    // $transactableModelClass and ID are for a tournament

                    // Withdraw funds from user
                    $userTransaction = new Transaction([
                        'transaction_id' => $this->transaction->transactableModelId,
                        'amount'         => -$this->transaction->amount,
                    ]);

                    $userTransaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;

                    User::find($this->transaction->userId)->transactions()->save($userTransaction);

                    // Add funds to tournament
                    $transaction = new Transaction([
                        'transaction_id' => $userTransaction->id,
                        'amount'         => $this->transaction->amount,
                    ]);

                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;

                case TransactionType::TOURNAMENT_PAYOUT:
                    // A tournament is transferring funds from its balance to a user
                    // $transactableModelClass and ID are for a user
                    // The transactionId is for the tournament

                    // Withdraw funds from tournament
                    $tournamentTransaction = new Transaction([
                        'transaction_id' => $this->transaction->transactableModelId,
                        'amount'         => -$this->transaction->amount,
                    ]);

                    $tournamentTransaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;

                    Tournament::find($this->transaction->transactionId)->transactions()->save($tournamentTransaction);

                    // Add funds to tournament
                    $transaction = new Transaction([
                        'transaction_id' => $tournamentTransaction->id,
                        'amount'         => $this->transaction->amount,
                    ]);

                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;

                case TransactionType::USER_WITHDRAWAL:
                    // A user is withdrawing funds from their balance
                    // so that we will compensate them

                    // Withdraw funds from user
                    $transaction = new Transaction([
                        'transaction_id' => $this->transaction->transactableModelId,
                        'amount'         => -$this->transaction->amount,
                    ]);
                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;

                case TransactionType::USER_REFUND:
                    // A user is withdrawing funds from their balance
                    // so that we will compensate them

                    // Withdraw funds from user
                    $transaction = new Transaction([
                        'transaction_id' => $this->transaction->transactableModelId,
                        'amount'         => $this->transaction->amount,
                    ]);
                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;

                case TransactionType::TK_PAYOUT:
                    // Remove funds from a tournament to record the payment to us

                    // Withdraw funds from tournament
                    $transaction = new Transaction([
                        'transaction_id' => $this->transaction->transactableModelId,
                        'amount'         => -$this->transaction->amount,
                    ]);
                    $transaction->transaction_type_name = TransactionType::findOrFail($transactionType)->name;
                break;
            }

            // Do the transaction
            $transactableModelClass::find($this->transaction->transactableModelId)
                ->transactions()
                ->save($transaction);

            // Do things after the transaction
            switch ($transactionType) {
                case TransactionType::TOURNAMENT_BUY_IN:
                    event(new BuyIn(Tournament::find($this->transaction->transactableModelId)));
                break;
                case TransactionType::USER_WITHDRAWAL:
                    $withdrawalData = [
                        'amount'               => $this->transaction->amount,
                    ];
                    $user = User::find($this->transaction->transactableModelId);

                    $withdrawalData = array_merge($withdrawalData, $this->transaction->data);
                    event(new UserWithdrawal($user, $withdrawalData));
                break;
                case TransactionType::USER_REFUND:
                    UserWithdrawalModel::find($this->transaction->transactionId)->delete();
                break;
                case TransactionType::TOURNAMENT_PAYOUT:
                    BalanceManager::completeTkPayout(Tournament::find($this->transaction->transactionId));
                break;
            }
        });
    }
}
