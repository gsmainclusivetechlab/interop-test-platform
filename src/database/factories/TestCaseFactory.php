<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Scenario;
use App\Models\TestCase;
use App\Models\UseCase;
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

$factory->define(TestCase::class, function (Faker $faker) {
    return [
        'use_case_id' => function () {
            return factory(UseCase::class)
                ->create()
                ->getKey();
        },
        'scenario_id' => function () {
            return factory(Scenario::class)
                ->create()
                ->getKey();
        },
        'name' => $faker->text,
        'slug' => $faker->slug,
        'behavior' => $faker->randomElement([
            TestCase::BEHAVIOR_POSITIVE,
            TestCase::BEHAVIOR_NEGATIVE,
        ]),
        'description' => $faker->text,
        'precondition' => $faker->text,
    ];
});
