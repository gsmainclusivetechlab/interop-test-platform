<?php declare(strict_types=1);

use App\Models\Api;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ApisTableSeeder extends Seeder
{
    /**
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    public function run()
    {
        foreach ($this->getData() as $key => $attributes) {
            Api::create(Arr::only($attributes, Api::make()->getFillable()))
                ->apiServers()->createMany(Arr::get($attributes, 'servers', []));
        }
    }

    /**
     * @return array
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Service Provider v1.0',
            ],
            [
                'name' => 'Mobile Money v1.1.2',
                'scheme' => Reader::readFromYamlFile(database_path('seeds/openapi/mm.yaml')),
                'servers' => [
                    [
                        'name' => 'MM Simulator',
                        'base_url' => env('FSIOP_MM_SIMULATOR_URL'),
                    ],
                ],
            ],
            [
                'name' => 'Mojaloop Hub v1.0',
                'scheme' => Reader::readFromYamlFile(database_path('seeds/openapi/mojaloop.yaml')),
                'servers' => [
                    [
                        'name' => 'Mojaloop Hub',
                        'base_url' => env('FSIOP_MOJALOOP_HUB_URL'),
                    ],
                ],
            ],
            [
                'name' => 'Mojaloop FSP v1.0',
                'scheme' => Reader::readFromYamlFile(database_path('seeds/openapi/mojaloop.yaml')),
                'servers' => [
                    [
                        'name' => 'Mojaloop Simulator',
                        'base_url' => env('FSIOP_MOJALOOP_SIMULATOR_URL'),
                    ],
                ],
            ],
        ];
    }
}
