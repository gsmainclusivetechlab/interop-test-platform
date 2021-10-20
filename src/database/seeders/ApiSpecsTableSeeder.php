<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApiSpec;
use cebe\openapi\Reader;
use Illuminate\Database\Seeder;
use Storage;
use Str;

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
        Storage::makeDirectory('openapis');
        $mmPath = 'openapis/' . Str::random(32) . '.yaml';
        $mojaPath = 'openapis/' . Str::random(32) . '.yaml';
        $mmSeedersPath = database_path('seeders/openapis/mm.yaml');
        $mojaSeedersPath = database_path('seeders/openapis/mojaloop.yaml');
        Storage::delete(Storage::allFiles('openapis'));

        return [
            [
                'name' => 'MM v1.1.2',
                'description' => '',
                'openapi' => Reader::readFromYamlFile($mmSeedersPath),
                'file_path' => \File::copy(
                    $mmSeedersPath,
                    \Storage::path($mmPath)
                )
                    ? $mmPath
                    : '',
            ],
            [
                'name' => 'Mojaloop v1.0',
                'description' => '',
                'openapi' => Reader::readFromYamlFile($mojaSeedersPath),
                'file_path' => \File::copy(
                    $mojaSeedersPath,
                    \Storage::path($mojaPath)
                )
                    ? $mojaPath
                    : '',
            ],
        ];
    }
}
