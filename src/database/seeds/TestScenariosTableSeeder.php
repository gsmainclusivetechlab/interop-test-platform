<?php

use App\Models\TestComponent;
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
        factory(TestScenario::class)->create(['name' => 'Mobile Money API and Mojaloop Hub'])->each(function ($scenario) {
            $scenario->components()->attach(TestComponent::pluck('id'));
        });
    }
}
