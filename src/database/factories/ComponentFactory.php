<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Component;
use App\Models\Scenario;
use App\Models\ApiService;
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

$factory->define(Component::class, function (Faker $faker) {
    return [
        'scenario_id' => function () {
            return factory(Scenario::class)->create()->id;
        },
        'api_service_id' => function () {
            return factory(ApiService::class)->create()->id;
        },
        'name' => $faker->text,
        'description' => $faker->text,
        'position' => $faker->numberBetween(1, 10),
    ];
});
