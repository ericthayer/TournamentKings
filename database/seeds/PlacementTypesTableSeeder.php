<?php

use Illuminate\Database\Seeder;
use App\Tournamentkings\Entities\Models\PlacementType;

class PlacementTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (PlacementType::all()->isEmpty()) {
            // Split percentage accounts for us keeping at least 5%
            $placementTypes = [
                'gold'   => .6,
                'silver' => .3,
                'bronze' => .1,
            ];

            collect($placementTypes)->each(function ($split, $name) {
                PlacementType::create(compact('name', 'split'));
            });
        }
    }
}
