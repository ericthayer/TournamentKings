<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\TournamentKings\Managers\RoundManager;

class TournamentByeFormatLimitations implements Rule
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
        $tournament = request()->route('tournament');
        if (! RoundManager::isPowerOfTwo($tournament->total_slots)) {
            if (RoundManager::isPowerOfTwo($value)) {
                return false;
            }
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
        return 'This tournament can only be updated to another bye format tournament.';
    }
}
