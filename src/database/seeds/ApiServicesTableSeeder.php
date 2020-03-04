<?php declare(strict_types=1);

use App\Models\ApiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class ApiServicesTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $data) {
            $versions = Arr::pull($data, 'versions', []);
            ApiService::create($data)->versions()->createMany($versions);
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money',
                'versions' => [
                    [
                        'uuid' => '6fd1452c-eae3-4019-a2b1-d6f1c6cff2d5',
                        'name' => 'v1.1.0',
                        'server' => env('FSIOP_MM_SIMULATOR_URL'),
                        'openapi' => Yaml::parseFile(database_path('seeds/openapi/mm-v1.1.0.yaml')),
                    ],
                ],
            ],
            [
                'name' => 'Mojaloop Hub',
                'versions' => [
                    [
                        'uuid' => 'c32ab451-9301-4a0d-9fb8-ab5ad9e68468',
                        'name' => 'v1.0',
                        'server' => env('FSIOP_MOJALOOP_HUB_URL'),
                        'openapi' => Yaml::parseFile(database_path('seeds/openapi/mojaloop-v1.0.yaml')),
                    ],
                ],
            ],
            [
                'name' => 'Mojaloop FSPIOP',
                'versions' => [
                    [
                        'uuid' => '4a4caa7e-dee4-4be6-83a3-d4db3c3ecebb',
                        'name' => 'v1.0',
                        'server' => env('FSIOP_MOJALOOP_SIMULATOR_URL'),
                        'openapi' => Yaml::parseFile(database_path('seeds/openapi/mojaloop-v1.0.yaml')),
                    ],
                ],
            ],
        ];
    }
}
