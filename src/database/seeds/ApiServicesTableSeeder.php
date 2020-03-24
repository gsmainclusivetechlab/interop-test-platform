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
            ApiService::create($attributes);
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
                'scheme' => new OpenApi(Yaml::parseFile(database_path('seeds/openapi/mm.yaml'))),
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
}
