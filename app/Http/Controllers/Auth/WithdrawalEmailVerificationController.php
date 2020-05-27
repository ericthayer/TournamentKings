<?php

namespace App\Http\Controllers\Auth;

use App\TournamentKings\Auth\VerifiesWithdrawalEmails;

class WithdrawalEmailVerificationController extends VerificationController
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesWithdrawalEmails;
}
