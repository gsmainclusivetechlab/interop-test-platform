<?php declare(strict_types=1);

use App\Models\ApiScheme;
use App\Models\ApiService;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;

class ApisTableSeeder extends Seeder
{
    /**
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    public function run()
    {
        foreach ($this->getApiSchemesData() as $key => $attributes) {
            ApiScheme::create($attributes);
        }

        foreach ($this->getApiServicesData() as $key => $attributes) {
            ApiService::create($attributes);
        }
    }

    /**
     * @return array
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    protected function getApiSchemesData()
    {
        return [
            [
                'name' => 'MM v1.1.2',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mm.yaml')),
            ],
            [
                'name' => 'Mojaloop v1.0',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mojaloop.yaml')),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getApiServicesData()
    {
        return [
            [
                'name' => 'SP Simulator',
                'base_url' => env('API_SERVICE_SP_SIMULATOR_URL'),
            ],
            [
                'name' => 'MM Simulator',
                'base_url' => env('API_SERVICE_MM_SIMULATOR_URL'),
            ],
            [
                'name' => 'Mojaloop Hub',
                'base_url' => env('API_SERVICE_MOJALOOP_HUB_URL'),
            ],
            [
                'name' => 'Mojaloop Simulator',
                'base_url' => env('API_SERVICE_MOJALOOP_SIMULATOR_URL'),
            ],
        ];
    }
}
