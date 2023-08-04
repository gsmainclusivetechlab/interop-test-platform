<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scenario;
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

$factory->define(Scenario::class, function (Faker $faker) {
    return [
        'name' => $faker->text,
        'description' => $faker->text,
        'use_case_id' => function () {
            return factory(UseCase::class)
                ->create()
                ->getKey();
        },
    ];
});
