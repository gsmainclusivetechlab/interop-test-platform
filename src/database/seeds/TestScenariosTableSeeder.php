<?php

use App\Models\Component;
use App\Models\Specification;
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
            $scenario->platforms()->createMany(Arr::get($this->getPlatformsData(), $key, []));
            $scenario->platformsConnections()->createMany(Arr::get($this->getPlatformsConnectionsData(), $key, []));
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API v1.0 and Mojaloop Hub API v1.0',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPlatformsData()
    {
        return [
            [
                [
                    'component_id' => Component::whereName('Payer')->value('id'),
                    'position' => 1,
                ],
                [
                    'component_id' => Component::whereName('Service Provider')->value('id'),
                    'position' => 2,
                ],
                [
                    'component_id' => Component::whereName('Mobile Money Operator 1')->value('id'),
                    'specification_id' => Specification::whereName('Mobile Money API v1.0')->value('id'),
                    'position' => 3,
                ],
                [
                    'component_id' => Component::whereName('Mojaloop System')->value('id'),
                    'specification_id' => Specification::whereName('Mojaloop Hub API v1.0')->value('id'),
                    'position' => 4,
                ],
                [
                    'component_id' => Component::whereName('Mobile Money Operator 2')->value('id'),
                    'specification_id' => Specification::whereName('Mojaloop Hub API v1.0')->value('id'),
                    'position' => 5,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPlatformsConnectionsData()
    {
        return [

        ];
    }
}
