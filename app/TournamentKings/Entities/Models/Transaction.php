<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'payment_processor',
        'amount',
        'transaction_type_name',
    ];

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_name', 'name');
    }

    public function transactable()
    {
        return $this->morphTo();
    }
}
