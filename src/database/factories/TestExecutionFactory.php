<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestExecution;
use App\Models\TestResult;
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
        'test_result_id' => function () {
            return factory(TestResult::class)->create()->id;
        },
        'name' => $faker->text,
        'actual' => \GuzzleHttp\json_encode($faker->rgbColorAsArray),
        'expected' => \GuzzleHttp\json_encode($faker->rgbColorAsArray),
        'exception' => $faker->text,
        'status' => $faker->randomElement([
            TestExecution::STATUS_PASS,
            TestExecution::STATUS_FAIL,
        ]),
    ];
});
