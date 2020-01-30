<?php

use App\Models\Component;
use App\Models\TestScenario;
use Illuminate\Database\Seeder;

class TestScenariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestScenario::class, 1)->create()->each(function ($scenario) {
            $scenario->components()->attach(Component::limit(5)->pluck('id'));
        });
    }
}
