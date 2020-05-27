<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function getBirthdayAttribute($value)
    {
        return new Carbon($value);
    }
}
