<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SufficientBalance implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $balanceData = auth()->user()->balance;

        if (! $balanceData) {
            return false;
        }

        $balance               = $balanceData->balance;
        $balanceIsInsufficient = bccomp($balance, $value) === -1;
        if ($balanceIsInsufficient) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('balance.insufficient-funds');
    }
}
