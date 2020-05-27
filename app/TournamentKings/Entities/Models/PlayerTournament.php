<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerTournament extends Model
{
    protected $table = 'player_tournament';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tournament_id', 'player_id',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
