<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UseCase;
use App\Models\Scenario;
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

$factory->define(UseCase::class, function (Faker $faker) {
    $scenarios = Scenario::all();
    return [
        'scenario_id' => $scenarios->count() > 0 ? Scenario::all()->random()->id : null,
        'name' => $faker->text,
        'description' => $faker->text,
    ];
});
