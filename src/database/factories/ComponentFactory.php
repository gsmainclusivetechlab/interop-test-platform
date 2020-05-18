<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Component;
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
        'name' => $faker->text,
        'base_url' => $faker->url,
        'description' => $faker->text,
    ];
});
