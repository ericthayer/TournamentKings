<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentBalance extends Model
{
    public $timestamps   = false;
    public $incrementing = false;
    public $primaryKey   = 'tournament_id';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
