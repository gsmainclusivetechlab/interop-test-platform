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
        foreach ($this->getData() as $key => $data) {
            $scenario = TestScenario::create($data);

            foreach (Arr::get($this->getComponentsData(), $key, []) as $componentData) {
                $scenario->components()->attach($componentData);
            }
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API and Mojaloop Hub API',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getComponentsData()
    {
        return [
            [
                [

                ],
            ],
        ];
    }
}
