<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    const SQUARE            = 'square';
    const TOURNAMENT_BUY_IN = 'internal_tournament-buy-in';
    const TOURNAMENT_PAYOUT = 'internal_tournament_payout';
    const USER_WITHDRAWAL   = 'internal_user_withdrawal';
    const USER_REFUND       = 'internal_user_refund';
    const TK_PAYOUT         = 'internal_tk_payout';

    protected $guarded = ['created_at', 'updated_at'];

    protected $primaryKey = 'name';
    public $incrementing  = false;
    protected $keyType    = 'string';

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_type_name', 'name');
    }
}
