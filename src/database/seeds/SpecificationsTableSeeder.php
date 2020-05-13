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
                'name' => 'MM v1.1.2',
                'description' => '',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mm.yaml')),
            ],
            [
                'name' => 'Mojaloop v1.0',
                'description' => '',
                'openapi' => Reader::readFromYamlFile(database_path('seeds/openapi/mojaloop.yaml')),
            ],
        ];
    }
}
