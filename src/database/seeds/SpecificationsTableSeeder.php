<?php declare(strict_types=1);

use App\Models\Specification;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    public function run()
    {
        foreach ($this->getSpecificationsData() as $key => $attributes) {
            Specification::create($attributes);
        }
    }

    /**
     * @return array
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    protected function getSpecificationsData()
    {
        return [
            [
                'name' => 'Mobile Money API',
                'version' => '1.1.2',
                'description' => 'This document defines the RESTful endpoints provided by the GSMA Mobile Money API. You can find out more about what the API can do for your business at [https://developer.mobilemoneyapi.io]',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mm.yaml')),
            ],
            [
                'name' => 'Mojaloop API',
                'version' => '1.0',
                'description' => 'Based on [API Definition version 1.0](https://github.com/mojaloop/mojaloop-specification/blob/develop/API%20Definition%20v1.0.pdf).',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mojaloop.yaml')),
            ],
        ];
    }
}
