<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use SquareConnect\Model\Money;
use SquareConnect\ApiException;
use App\Jobs\ProcessTransaction;
use SquareConnect\Configuration;
use Illuminate\Support\MessageBag;
use App\Http\Requests\SquarePayment;
use SquareConnect\Api\TransactionsApi;
use SquareConnect\Model\ChargeRequest;
use App\Http\Requests\TournamentPayment;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Managers\BalanceManager;
use App\Tournamentkings\Entities\Models\Tournament;
use App\TournamentKings\Entities\Models\WithdrawalType;
use App\TournamentKings\Entities\Models\TransactionType;
use App\Http\Requests\UserWithdrawal as UserWithdrawalRequest;
use App\TournamentKings\Payment\Transaction as PaymentTransaction;

class BalanceController extends Controller
{
    public function showBalance()
    {
        return view('balance.show');
    }

    public function deposit()
    {
        return view('balance.deposit');
    }

    public function processPayment(SquarePayment $request)
    {
        $validatedData = $request->validated();

        $nonce  = $validatedData['nonce'];
        $amount = $validatedData['amount'];

        $errors = new MessageBag;

        // Square will (wisely) avoid floating point arithmetic
        $amountForRequest = intval(floatval($amount) * 100);

        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $locationId  = env('SQUARE_LOCATION_ID');

        Configuration::getDefaultConfiguration()->setAccessToken($accessToken);

        $transactionsApi = new TransactionsApi;

        $squareRequest = new ChargeRequest;
        $squareRequest->setIdempotencyKey(Uuid::uuid1()->toString());
        $squareRequest->setCardNonce($nonce);

        $money = new Money;
        $money->setAmount($amountForRequest);
        $money->setCurrency('USD');

        $squareRequest->setAmountMoney($money);

        try {
            $result = $transactionsApi->charge($locationId, $squareRequest);
        } catch (ApiException $e) {
            $errorResponse = $e->getResponseBody();
            foreach ($errorResponse->errors as $key => $error) {
                $errors->add($error->code, $error->detail);
            }
        }

        if ($errors->isEmpty()) {
            // We may now process the payment in our system.
            $squareTransactionData = json_decode($result);
            $transaction           = new PaymentTransaction();

            // Set the amount based on what Square told us they received
            $tenders = $squareTransactionData->transaction->tenders;

            $intAmount = collect($tenders)->reduce(function ($acc, $cur) {
                return $cur->amount_money->amount + $acc;
            }, 0);
            $floatAmount = floatval($intAmount) / 100;

            $transaction->amount                 = $floatAmount;
            $transaction->transactionType        = 'square';
            $transaction->transactionId          = $squareTransactionData->transaction->id;
            $transaction->transactableModelId    = auth()->user()->id;
            $transaction->transactableModelClass = User::class;

            $errors = $this->makeTransaction($transaction);
        }

        $redirect = redirect(route('balance.show'));

        if ($errors->isNotEmpty()) {
            $redirect = redirect(route('balance.deposit.create'));
            $redirect->withErrors($errors)->with('amount', $amount);
        } else {
            $redirect->with(
                [
                    'success' => __('balance.deposit.success'),
                ]
            );
        }

        return $redirect;
    }

    public function withdrawal()
    {
        return view('balance.withdrawal')->with([
            'withdrawal_types' => WithdrawalType::getMappedTypes(),
        ]);
    }

    public function postWithdrawal(UserWithdrawalRequest $request)
    {
        // Start out by recording the withdrawal

        $validatedData = $request->validated();
        $email         = $validatedData['email'];

        // See if they entered an email that has previously been verified for withdrawals
        $emailVerified = auth()->user()
            ->withdrawals
            ->where('email', $email)
            ->where('email_verified_at', '<>', null)
            ->isNotEmpty();

        if (empty($email) || $email === auth()->user()->email) {
            $email         = auth()->user()->email;
            $emailVerified = true;
        }

        $email_verified_at = null;
        if ($emailVerified) {
            $email_verified_at = now();
        }

        $validatedData = array_merge($validatedData, compact('email', 'email_verified_at'));

        $errors   = BalanceManager::completeUserWithdrawal($validatedData);
        $redirect = redirect(route('balance.show'));

        // If there was a problem, just send them back
        if ($errors->isNotEmpty()) {
            $redirect = back();
            $redirect->withErrors($errors)->withInput();

            return $redirect;
        }

        if (! $emailVerified) {
            $redirect->with(
                [
                    'success' => __('balance.withdrawal.success').'<br />'.__('balance.withdrawal.verify-email'),
                ]
            );
        } else {
            $redirect->with(
                [
                    'success' => __('balance.withdrawal.success'),
                ]
            );
        }

        return $redirect;
    }

    public function transferFundsToTournament(Tournament $tournament, TournamentPayment $request)
    {
        $validatedData = $request->validated();

        $transaction = BalanceManager::setupToTournamentTransaction($tournament, $validatedData);
        $errors      = BalanceManager::makeTransaction($transaction);

        $redirect = redirect(route('tournaments.show', $tournament));

        if ($errors->isNotEmpty()) {
            $redirect = $redirect->withErrors($errors);
        } else {
            $redirect->with(
              [
                  'success' => __('balance.deposit.success'),
              ]
            );
        }

        return $redirect;
    }

    private function makeTransaction(
        PaymentTransaction $transaction
    ): MessageBag {
        $errors = new MessageBag;

        /*
         * In PHP, we should be careful to only do math with integers that represent the
         * floating point values.
         *
         * Multiplying and dividing floating point numbers by factors of 10 should be okay.
         *
         * In MySQL, the DECIMAL datatype can do math with such numbers much more accurately.
         */
        switch ($transaction->transactionType) {
            case TransactionType::TOURNAMENT_BUY_IN:
                $balanceData = auth()->user()->balance;

                if (! $balanceData) {
                    $errors->add('no-balance', __('balance.no-balance'));
                    break;
                }

                $balance = intval($balanceData->balance * 100);
                if ($balance - intval($transaction->amount * 100) < 0) {
                    $errors->add('insufficient-funds', __('balance.no-balance'));
                    break;
                }
            break;
            case TransactionType::SQUARE:

            break;
        }

        if ($errors->isEmpty()) {
            dispatch(new ProcessTransaction($transaction));
        }

        return $errors;
    }
}
