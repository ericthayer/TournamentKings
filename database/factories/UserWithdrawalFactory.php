<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Entities\Models\UserWithdrawal;
use App\TournamentKings\Entities\Models\WithdrawalType;

$factory->define(UserWithdrawal::class, function (Faker $faker) {
    $amount = $faker->randomFloat(1, 100, 1000);
    $phone_number = $faker->phoneNumber;
    $email = $faker->email;
    $withdrawal_type_name = WithdrawalType::PAYPAL;
    $user_id = User::role('Admin')->first()->id;

    return compact('amount', 'phone_number', 'email', 'user_id', 'withdrawal_type_name');
});

$factory->state(UserWithdrawal::class, 'email_verified', function (Faker $faker) {
    $email_verified_at = now();

    return compact('email_verified_at');
});

$factory->state(UserWithdrawal::class, 'no_phone', function (Faker $faker) {
    $phone_number = '';

    return compact('phone_number');
});
