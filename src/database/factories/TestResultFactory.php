<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestRun;
use App\Models\TestResult;
use App\Models\TestStep;
use Faker\Generator as Faker;
use App\Http\Client\Request;
use App\Http\Client\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response as PsrResponse;

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

$factory->define(TestResult::class, function (Faker $faker) {
    return [
        'test_run_id' => function () {
            return factory(TestRun::class)->create()->id;
        },
        'test_step_id' => function () {
            return factory(TestStep::class)->create()->id;
        },
        'request' => (new Request(new ServerRequest('get', 'test'))),
        'response' => (new Response(new PsrResponse())),
        'exception' => $faker->text,
        'status' => $faker->randomElement([
            TestResult::STATUS_INCOMPLETE,
            TestResult::STATUS_PASS,
            TestResult::STATUS_FAIL,
        ]),
    ];
});
