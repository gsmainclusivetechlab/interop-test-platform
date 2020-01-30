<?php

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
        factory(TestSuite::class)->create(['name' => 'Merchant Initiated Flow']);
    }
}
