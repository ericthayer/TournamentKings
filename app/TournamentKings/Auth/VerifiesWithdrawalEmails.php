<?php

namespace App\Tournamentkings\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use App\Tournamentkings\Entities\Models\UserWithdrawal;

trait VerifiesWithdrawalEmails
{
    use RedirectsUsers, VerifiesEmails;

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $userWithdrawalId = $request->route('id');
        $userWithdrawal   = $request->user()->withdrawals->find($userWithdrawalId);
        if (! $userWithdrawal) {
            throw new AuthorizationException;
        }

        if ($userWithdrawal->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($userWithdrawal->markEmailAsVerified()) {
            //event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request, UserWithdrawal $userWithdrawal = null)
    {
        if (! $userWithdrawal) {
            return back();
        }
        if ($userWithdrawal->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $userWithdrawal->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
