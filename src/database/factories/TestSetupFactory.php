<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TestSetup;
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

$factory->define(\App\Models\TestSetup::class, function (Faker $faker) {
    return [
        'test_step_id' => function () {
            return factory(TestStep::class)->create()->id;
        },
        'name' => $faker->text,
        'type' => $faker->randomElement([
            TestSetup::TYPE_REQUEST,
            TestSetup::TYPE_RESPONSE,
        ]),
        'values' => \GuzzleHttp\json_encode($faker->rgbColorAsArray),
        'position' => $faker->numberBetween(1, 10),
    ];
});
