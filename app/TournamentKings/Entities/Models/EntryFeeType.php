<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class EntryFeeType extends Model
{
    const FREE       = 'free';
    const TARGET_POT = 'target-pot';
    const FLAT_FEE   = 'flat-fee';

    protected $guarded = [];

    protected $primaryKey = 'name';
    public $incrementing  = false;
    protected $keyType    = 'string';

    /**
     * Return a collection that maps the entry fee type names as keys
     * to their display names as values.
     *
     * @return Collection
     */
    public static function getMappedTypes(): Collection
    {
        return self::all()
            ->mapWithKeys(function ($entryFeeType) {
                return [$entryFeeType->name => $entryFeeType->display_name];
            });
    }

    public function getIsFreeAttribute()
    {
        return $this->name === self::FREE;
    }

    public function getIsFlatFeeAttribute()
    {
        return $this->name === self::FLAT_FEE;
    }

    public function getIsTargetPotAttribute()
    {
        return $this->name === self::TARGET_POT;
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'entry_fee_type_name', 'name');
    }
}
