<?php

namespace App\Rules;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class TargetPot implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tournament       = request()->route('tournament');
        $this->playerDepositInt = intval($this->tournament->player_deposit * 100);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->amountInt = intval(floatval($value) * 100);

        $negativeTransactionSum = auth()->user()
            ->transactions()
            ->where('transaction_type_name', 'internal_tournament-buy-in')
            ->where('transaction_id', $this->tournament->id)
            ->sum('amount');
        $positiveTransactionSum = abs($negativeTransactionSum);
        $transactionSumIntVal   = intval($positiveTransactionSum * 100);

        return $this->playerDepositInt === $this->amountInt;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Please pay the exact remaining player deposit: {$this->tournament->display_player_deposit}";
    }
}
