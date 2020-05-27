<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class WithdrawalType extends Model
{
    const SQUARE_CASH = 'square cash';
    const VENMO       = 'venmo';
    const PAYPAL      = 'paypal';

    protected $fillable = ['name'];

    public static function typeNameList()
    {
        return [
            self::SQUARE_CASH,
            self::VENMO,
            self::PAYPAL,
        ];
    }

    /**
     * Return a collection that maps the withdrawal type names as keys
     * to their display names as values.
     *
     * @return Collection
     */
    public static function getMappedTypes(): Collection
    {
        return self::all()
            ->mapWithKeys(function ($withdrawalType) {
                return [$withdrawalType->name => $withdrawalType->display_name];
            });
    }

    public function getDisplayNameAttribute()
    {
        return ucwords($this->name);
    }

    public function userWithdrawal()
    {
        return $this->hasMany(UserWithdrawal::class, 'withdrawal_type_name', 'name');
    }
}
