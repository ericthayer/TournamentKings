<?php

use Illuminate\Database\Seeder;
use App\TournamentKings\Entities\Models\WithdrawalType;

class WithdrawalTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'square cash',
            'venmo',
            'paypal',
        ];

        if (WithdrawalType::all()->isEmpty()) {
            collect($names)->each(function ($name) {
                WithdrawalType::create(['name' => $name]);
            });
        }
    }
}
