<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestExecution;
use App\Models\TestResult;
use App\Models\TestCase;
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

$factory->define(TestExecution::class, function (Faker $faker) {
    return [
        'test_step_id' => function () {
            return factory(TestStep::class)
                ->create()
                ->getKey();
        },
        'test_run_id' => function () {
            return factory(TestRun::class)
                ->create()
                ->getKey();
        },
        'name' => $faker->text,
        'actual' => $faker->randomElements(),
        'expected' => $faker->randomElements(),
    ];
});
