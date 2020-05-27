<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'user_id';
    public $incrementing  = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
