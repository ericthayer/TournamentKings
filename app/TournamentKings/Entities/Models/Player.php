<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'gamer_tag',
        'platform_type_id',
        'location_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class, 'match_player');
    }

    public function platformType()
    {
        return $this->belongsTo(PlatformType::class);
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'player_tournament');
    }

    public function placements()
    {
        return $this->belongsToMany(Placement::class, 'placements');
    }
}
