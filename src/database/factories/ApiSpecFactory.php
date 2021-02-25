<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ApiSpec;
use cebe\openapi\Reader;
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

$factory->define(ApiSpec::class, function (Faker $faker) {
    return [
        'name' => $faker->text,
        'openapi' => function () use ($faker) {
            return Reader::readFromJsonFile(
                'https://petstore.swagger.io/v2/swagger.json'
            );
        },
        'description' => $faker->text,
        'file_path' => 'openapis/' . Str::random(32) . '.yaml',
    ];
});
