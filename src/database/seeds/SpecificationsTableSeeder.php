<?php declare(strict_types=1);

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
                'uuid' => '6fd1452c-eae3-4019-a2b1-d6f1c6cff2d5',
                'name' => 'Mobile Money API v1.1.0',
                'schema' => Yaml::parseFile(database_path('schemas/mm.api.yaml')),
            ],
            [
                'uuid' => 'c32ab451-9301-4a0d-9fb8-ab5ad9e68468',
                'name' => 'Mojaloop FSPIOP API v1.0',
                'schema' => Yaml::parseFile(database_path('schemas/mojaloop.api.yaml')),
            ],
        ];
    }
}
