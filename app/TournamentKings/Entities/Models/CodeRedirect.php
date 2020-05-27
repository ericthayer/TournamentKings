<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class CodeRedirect extends Model
{
    public $table      = 'code_redirect';
    public $timestamps = true;
    protected $guarded = [];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = str_replace(' ', '', $value);
    }
}
