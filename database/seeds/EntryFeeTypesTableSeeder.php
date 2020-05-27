<?php

use Illuminate\Database\Seeder;
use App\Tournamentkings\Entities\Models\EntryFeeType;

class EntryFeeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'free'       => 'Free',
            'flat-fee'   => 'Flat Fee',
            'target-pot' => 'Target Pot',
        ];

        if (EntryFeeType::all()->isEmpty()) {
            collect($names)->each(function ($displayName, $name) {
                EntryFeeType::create([
                    'display_name' => $displayName,
                    'name'         => $name,
                ]);
            });
        }
    }
}
