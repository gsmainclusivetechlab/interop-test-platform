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
                'name' => 'Mobile Money API v1.1.0',
                'schema' => Yaml::parseFile(database_path('schemas/mm.api.yaml')),
            ],
            [
                'name' => 'Mojaloop FSPIOP API v1.0',
                'schema' => Yaml::parseFile(database_path('schemas/mojaloop.api.yaml')),
            ],
        ];
    }
}
