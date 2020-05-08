<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Session;
use App\Models\Scenario;
use App\Models\User;
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

$factory->define(Session::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
        'scenario_id' => function () {
            return factory(Scenario::class)->create()->id;
        },
        'name' => $faker->text,
        'description' => $faker->text,
    ];
});
