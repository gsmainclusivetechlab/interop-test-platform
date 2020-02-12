<?php

use App\Models\Specification;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Specification::class)->createMany($this->getData())->each(function ($specification, $key) {
            foreach (Arr::get($this->getVersionsData(), $key, []) as $version) {
                $specification->versions()->create($version);
            }
        });
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'GSMA MM',
            ],
            [
                'name' => 'Mojaloop Hub',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getVersionsData()
    {
        return [
            [
                [
                    'version' => '1.0',
                    'schema' => (array) Reader::readFromYamlFile('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml')
                        ->getSerializableData(),
                ],
            ],
        ];
    }
}
