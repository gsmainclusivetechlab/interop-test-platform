<?php declare(strict_types=1);

use App\Models\ApiService;
use cebe\openapi\spec\OpenApi;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class ApiServicesTableSeeder extends Seeder
{
    /**
     * @throws \cebe\openapi\exceptions\TypeErrorException
     */
    public function run()
    {
        foreach ($this->getData() as $key => $attributes) {
            $service = ApiService::create($attributes);
            $service->update(['uuid' => Arr::get($this->getUuids(), $key)]);
        }
    }

    /**
     * @return array
     * @throws \cebe\openapi\exceptions\TypeErrorException
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money v1.1.0',
                'server' => env('FSIOP_MM_SIMULATOR_URL'),
                'scheme' => new OpenApi(Yaml::parseFile(database_path('seeds/openapi/mobile-money.yaml'))),
            ],
            [
                'name' => 'Mojaloop Hub v1.0',
                'server' => env('FSIOP_MOJALOOP_HUB_URL'),
                'scheme' => new OpenApi(Yaml::parseFile(database_path('seeds/openapi/mojaloop.yaml'))),
            ],
            [
                'name' => 'Mojaloop FSP v1.0',
                'server' => env('FSIOP_MOJALOOP_SIMULATOR_URL'),
                'scheme' => new OpenApi(Yaml::parseFile(database_path('seeds/openapi/mojaloop.yaml'))),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getUuids()
    {
        return [
            '6fd1452c-eae3-4019-a2b1-d6f1c6cff2d5',
            'c32ab451-9301-4a0d-9fb8-ab5ad9e68468',
            '4a4caa7e-dee4-4be6-83a3-d4db3c3ecebb',
        ];
    }
}
