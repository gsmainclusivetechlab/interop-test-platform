<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Group;
use App\Models\GroupUserInvitation;
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

$factory->define(GroupUserInvitation::class, function (Faker $faker) {
    return [
        'group_id' => function () {
            return factory(Group::class)
                ->create()
                ->getKey();
        },
        'email' => $faker->text,
        'expired_at' => $faker->date(),
    ];
});
