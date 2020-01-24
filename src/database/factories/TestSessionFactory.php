<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestSession;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(TestSession::class, function (Faker $faker) {
    return [
        'uuid' => Str::uuid(),
        'name' => $faker->sentence(),
    ];
});
