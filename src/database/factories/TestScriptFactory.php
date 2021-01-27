<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestScript;
use App\Models\TestStep;
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

$factory->define(TestScript::class, function (Faker $faker) {
    return [
        'test_step_id' => function () {
            return factory(TestStep::class)
                ->create()
                ->getKey();
        },
        'name' => $faker->text,
        'type' => $faker->randomElement([
            TestScript::TYPE_REQUEST,
            TestScript::TYPE_RESPONSE,
        ]),
        'rules' => $faker->randomElements(),
        'messages' => $faker->randomElements(),
    ];
});
