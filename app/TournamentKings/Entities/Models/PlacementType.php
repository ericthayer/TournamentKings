<?php

namespace App\Tournamentkings\Entities\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementType extends Model
{
    const GOLD   = 'gold';
    const SILVER = 'silver';
    const BRONZE = 'bronze';

    public static function gold()
    {
        return self::find(self::GOLD);
    }

    public static function silver()
    {
        return self::find(self::SILVER);
    }

    public static function bronze()
    {
        return self::find(self::BRONZE);
    }

    public static function prizeNameList()
    {
        return [
            self::GOLD,
            self::SILVER,
            self::BRONZE,
        ];
    }

    public $incrementing  = false;
    protected $primaryKey = 'name';
    protected $keyType    = 'string';

    protected $guarded = '';

    public function placements()
    {
        $this->hasMany(Placement::class, 'placement_type_name', 'name');
    }

    public function getDisplayNameAttribute()
    {
        return ucfirst($this->name);
    }
}
