<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ApiSpec;
use App\Models\TestCase;
use App\Models\Component;
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

$factory->define(TestStep::class, function (Faker $faker) {
    $sourceComponent = factory(Component::class)->create();
    $targetComponent = factory(Component::class)->create();

    return [
        'test_case_id' => function () {
            return factory(TestCase::class)
                ->create()
                ->getKey();
        },
        'api_spec_id' => function () {
            return factory(ApiSpec::class)
                ->create()
                ->getKey();
        },
        'source_id' => $sourceComponent->getKey(),
        'target_id' => $targetComponent->getKey(),
        'path' => $faker->text,
        'method' => $faker->text,
        'pattern' => $faker->text,
        'trigger' => $faker->randomElements(),
        'request' => new Request(new ServerRequest('get', $faker->url)),
        'response' => new Response(new PsrResponse()),
    ];
});
