<?php

namespace App\Tournamentkings\Entities\Models;

use Laravel\Spark\User as SparkUser;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends SparkUser implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'two_factor_reset_code',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
    ];

    protected $with = [
        'player',
        'players',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at'        => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function balance()
    {
        return $this->hasOne(UserBalance::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(UserWithdrawal::class);
    }
}
