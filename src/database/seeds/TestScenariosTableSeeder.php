<?php

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
        $data = $this->getData();

        foreach ($data as $row) {
            factory(TestScenario::class)->create($row);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            ['name' => 'Mobile Money API and Mojaloop Hub'],
        ];
    }
}
