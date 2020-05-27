<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Tournamentkings\Auth\Notifications\VerifyWithdrawalEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class UserWithdrawal extends Model implements MustVerifyEmailContract
{
    use SoftDeletes, MustVerifyEmail, Notifiable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawalType()
    {
        return $this->belongsTo(WithdrawalType::class, 'withdrawal_type_name', 'name');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyWithdrawalEmail);
    }
}
