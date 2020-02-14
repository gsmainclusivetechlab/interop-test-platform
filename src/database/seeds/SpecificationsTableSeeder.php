<?php

use App\Models\Specification;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $key => $data) {
            Specification::create($data);
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API v1.0',
                'server' => '{MM_API_HOST}',
                'schema' => Yaml::parse(file_get_contents('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml')),
            ],
            [
                'name' => 'Mojaloop Hub API v1.0',
                'server' => '{MOJALOOP_API_HOST}',
                'schema' => Yaml::parse(file_get_contents('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml')),
            ],
        ];
    }
}
