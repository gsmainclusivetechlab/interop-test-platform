<?php

use App\Imports\TestCaseImport;
use App\Models\ApiService;
use App\Models\Component;
use App\Models\Scenario;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class ScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        $scenario = Scenario::create(['name' => 'Mobile Money API v1.1.0 and Mojaloop FSPIOP API v1.0']);
        $scenario->useCases()->createMany($this->getUseCasesData());
        $scenario->components()->createMany($this->getComponentsData())->each(function (Component $component, $key) use ($scenario) {
            $component->paths()->attach(Arr::get($this->getComponentPathsData($scenario), $key));
        });

        $finder = (new Finder())
            ->files()
            ->name('*.yaml')
            ->in(database_path('seeds/tc'));
//            ->in(database_path('seeds/test-cases'));

        foreach ($finder as $file) {
            /**
             * @var SplFileInfo $file
             */
            (new TestCaseImport($scenario))->import(Yaml::parse($file->getContents()));
        }
    }

    /**
     * @return array
     */
    public function getUseCasesData()
    {
        return [
            [
              'name' => 'Merchant-Initiated Merchant Payment',
              'description' => 'A Merchant-Initiated Merchant Payment is typically a receive amount, where the Payer FSP is not disclosing any fees to the Payee FSP. Please refer to 5.1.6.8 in "Open API for FSP Interoperability Specification" for more details'
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getComponentsData()
    {
        return [
            [
                'name' => 'Payer',
            ],
            [
                'name' => 'Service Provider',
                'sut' => true,
                'simulated' => true,
            ],
            [
                'name' => 'Mobile Money Operator 1',
                'api_service_id' => ApiService::where(['name' => 'Mobile Money v1.1.0'])->value('id'),
                'simulated' => true,
            ],
            [
                'name' => 'Mojaloop',
                'api_service_id' => ApiService::where(['name' => 'Mojaloop Hub v1.0'])->value('id'),
                'simulated' => true,
            ],
            [
                'name' => 'Mobile Money Operator 2',
                'api_service_id' => ApiService::where(['name' => 'Mojaloop FSP v1.0'])->value('id'),
                'simulated' => true,
            ],
        ];
    }

    /**
     * @param Scenario $scenario
     * @return array
     */
    protected function getComponentPathsData(Scenario $scenario)
    {
        return [
            [
                [
                    'target_id' => $scenario->components()->where('name', 'Service Provider')->value('id'),
                ],
            ],
            [
                [
                    'target_id' => $scenario->components()->where('name', 'Payer')->value('id'),
                ],
                [
                    'target_id' => $scenario->components()->where('name', 'Mobile Money Operator 1')->value('id'),
                ],
            ],
            [
                [
                    'target_id' => $scenario->components()->where('name', 'Service Provider')->value('id'),
                ],
                [
                    'target_id' => $scenario->components()->where('name', 'Mojaloop')->value('id'),
                ],
            ],
            [
                [
                    'target_id' => $scenario->components()->where('name', 'Mobile Money Operator 1')->value('id'),
                ],
                [
                    'target_id' => $scenario->components()->where('name', 'Mobile Money Operator 2')->value('id'),
                ],
            ],
            [
                [
                    'target_id' => $scenario->components()->where('name', 'Mojaloop')->value('id'),
                ],
            ],
        ];
    }
}
