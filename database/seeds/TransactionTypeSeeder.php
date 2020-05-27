<?php

use Illuminate\Database\Seeder;
use App\TournamentKings\Entities\Models\TransactionType;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'square',
            'internal_tournament-buy-in',
            'internal_tournament_payout',
            'internal_user_withdrawal',
            'internal_user_refund',
            'internal_tk_payout',
        ];

        if (TransactionType::all()->isEmpty()) {
            collect($names)->each(function ($name) {
                TransactionType::create(['name' => $name]);
            });
        }
    }
}
