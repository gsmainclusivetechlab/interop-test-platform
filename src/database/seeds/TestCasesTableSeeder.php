<?php

use App\Models\TestCase;
use App\Models\TestSuite;
use Illuminate\Database\Seeder;

class TestCasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TestSuite::first()->cases()->saveMany(factory(TestCase::class, 5)->make(['behavior' => TestCase::BEHAVIOR_POSITIVE]));
        TestSuite::first()->cases()->saveMany(factory(TestCase::class, 5)->make(['behavior' => TestCase::BEHAVIOR_NEGATIVE]));
    }
}
