<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'company' => $faker->company,
        'role' => $faker->randomElement([
            User::ROLE_USER,
            User::ROLE_ADMIN,
            User::ROLE_SUPERADMIN,
        ]),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $faker->dateTime,
        'password' => Hash::make('password'),
        'remember_token' => Str::random(100),
    ];
});
