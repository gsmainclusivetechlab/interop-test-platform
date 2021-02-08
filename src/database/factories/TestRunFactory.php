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
    return [
        'session_id' => function () {
            return factory(Session::class)
                ->create()
                ->getKey();
        },
        'test_case_id' => function () {
            return factory(TestCase::class)
                ->create()
                ->getKey();
        },
    ];
});
