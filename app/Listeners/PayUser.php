<?php

namespace App\Listeners;

use App\Events\UserWithdrawal as UserWithdrawalEvent;
use App\TournamentKings\Entities\Models\UserWithdrawal;

class PayUser
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserWithdrawalEvent $event)
    {
        $this->makeWithdrawal($event);
    }

    /**
     * For now, this just entails saving the withdrawal submission to.
     */
    public function makeWithdrawal(UserWithdrawalEvent $event)
    {
        $userWithdrawal = UserWithdrawal::create([
            'user_id'               => $event->user->id,
            'amount'                => $event->data['amount'],
            'phone_number'          => $event->data['phone_number'],
            'email'                 => $event->data['email'],
            'email_verified_at'     => $event->data['email_verified_at'],
            'withdrawal_type_name'  => $event->data['withdrawal_type_name'],
        ]);

        if (! $userWithdrawal->email_verified_at) {
            $userWithdrawal->sendEmailVerificationNotification();
        }
    }
}
