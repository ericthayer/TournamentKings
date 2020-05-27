<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    protected $guarded    = ['created_at', 'updated_at'];
    protected $primaryKey = 'player_tournament_id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    public function placementType()
    {
        return $this->belongsTo(PlacementType::class, 'placement_type_name', 'name');
    }

    public function playerTournament()
    {
        return $this->belongsTo(PlayerTournament::class);
    }
}
