<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ApiScheme;
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
    $sourceId = factory(Component::class)->create()->id;
    $targetId = factory(Component::class)->create()->id;
    DB::table('component_connections')->insert([
        'source_id' => $sourceId,
        'target_id' => $targetId,
    ]);

    return [
        'test_case_id' => function () {
            return factory(TestCase::class)->create()->id;
        },
        'source_id' => $sourceId,
        'target_id' => $targetId,
        'api_scheme_id' => function () {
            return factory(ApiScheme::class)->create()->id;
        },
        'name' => $faker->text,
        'request' => (new Request(new ServerRequest('get', 'test'))),
        'response' => (new Response(new PsrResponse())),
        'position' => $faker->numberBetween(0, 10),
    ];
});
