<?php declare(strict_types=1);

use App\Models\ApiSpec;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;

class ApiSpecsTableSeeder extends Seeder
{
    /**
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    public function run()
    {
        factory(ApiSpec::class)->createMany($this->getApiSpecsData());
    }

    /**
     * @return array
     * @throws \cebe\openapi\exceptions\IOException
     * @throws \cebe\openapi\exceptions\TypeErrorException
     * @throws \cebe\openapi\exceptions\UnresolvableReferenceException
     */
    protected function getApiSpecsData()
    {
        return [
            [
                'name' => 'MM v1.1.2',
                'description' => '',
                'openapi' => Reader::readFromYamlFile(
                    database_path('seeds/openapis/mm.yaml')
                ),
            ],
            [
                'name' => 'Mojaloop v1.0',
                'description' => '',
                'openapi' => Reader::readFromYamlFile(
                    database_path('seeds/openapis/mojaloop.yaml')
                ),
            ],
        ];
    }
}
