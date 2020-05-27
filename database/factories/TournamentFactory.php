<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Tournamentkings\Entities\Models\User;
use App\Tournamentkings\Entities\Models\GameType;
use App\Tournamentkings\Entities\Models\Tournament;
use App\Tournamentkings\Entities\Models\EntryFeeType;

$factory->define(Tournament::class, function (Faker $faker) {
    return [
        'name'                     => $faker->catchPhrase,
        'description'              => $faker->sentence,
        'total_slots'              => 4,
        'tournament_type'          => 'public',
        'start_datetime'           => $faker->dateTimeBetween('+ 1 day', '+2 days'),
        'game_type_id'             => GameType::first()->id,
        'created_by_player_id'     => User::role('Admin')->first()->player->id,
        'entry_fee_type_name'      => EntryFeeType::FREE,
    ];
});

$factory->state(Tournament::class, EntryFeeType::FLAT_FEE, function (Faker $faker) {
    return [
        'entry_fee_type_name' => 'flat-fee',
        'entry_fee'           => $faker->randomFloat(2, 10, 1000),
    ];
});

$factory->state(Tournament::class, EntryFeeType::TARGET_POT, function (Faker $faker) {
    return [
        'entry_fee_type_name' => 'target-pot',
        'target_pot'          => $faker->randomFloat(2, 10, 1000),
    ];
});
