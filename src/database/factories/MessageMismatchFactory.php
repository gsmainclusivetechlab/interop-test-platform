<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MessageMismatch;
use Faker\Generator as Faker;

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

$factory->define(MessageMismatch::class, function (Faker $faker) {
    return [
        'request' => $faker->randomElements(),
        'exception' => $faker->text,
    ];
});
