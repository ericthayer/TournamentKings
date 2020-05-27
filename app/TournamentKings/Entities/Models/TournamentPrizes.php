<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentPrizes extends Model
{
    protected $guarded = ['created_at', 'updated_at'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function placementType()
    {
        return $this->belongsTo(PlacementType::class, 'placement_type_name', 'name');
    }

    public function getDisplayAmountAttribute()
    {
        setlocale(LC_MONETARY, config('app.monetary_locale'));

        return money_format('%+.2n', $this->amount);
    }
}
