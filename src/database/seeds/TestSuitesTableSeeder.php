<?php

use App\Models\TestScenario;
use App\Models\TestSuite;
use Illuminate\Database\Seeder;

class TestSuitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TestScenario::first()->suites()->saveMany(factory(TestSuite::class, 1)->make(['name' => 'Merchant Initiated Flow']));
    }
}
