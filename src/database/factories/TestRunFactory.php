<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
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

$factory->define(TestRun::class, function (Faker $faker) {
    $failures = $faker->numberBetween(0, 10);
    $passed = $faker->numberBetween(0, 10);
    $sessionId = factory(Session::class)->create()->id;
    $testCaseId = factory(TestCase::class)->create()->id;
    DB::table('session_test_cases')->insert([
        'session_id' => $sessionId,
        'test_case_id' => $testCaseId,
    ]);

    return [
        'uuid' => $faker->uuid,
        'session_id' => $sessionId,
        'test_case_id' => $testCaseId,
        'total' => $passed + $failures,
        'passed' => $passed,
        'failures' => $failures,
        'duration' => $faker->numberBetween(100, 1000),
    ];
});
