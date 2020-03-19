<?php

use App\Models\ApiService;
use App\Models\Component;
use App\Models\Scenario;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Symfony\Component\Finder\Finder;

class ScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $scenario = Scenario::create(['name' => 'Mobile Money API v1.1.0 and Mojaloop FSPIOP API v1.0']);
        $scenario->useCases()->createMany($this->getUseCasesData());
        $scenario->components()->createMany($this->getComponentsData())->each(function (Component $component, $key) {
            $component->paths()->attach(Arr::get($this->getComponentPathsData(), $key));
        });

//        $finder = new Finder();
//        $finder->files()->name('*.yaml')->in(database_path('seeds/test-cases'));
//
//        foreach ($finder as $item) {
//            dd($item->getContents());
//        }
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
            ],
            [
                'name' => 'Mobile Money Operator 1',
                'api_service_id' => ApiService::where(['name' => 'Mobile Money', 'version' => 'v1.1.0'])->value('id'),
            ],
            [
                'name' => 'Mojaloop',
                'api_service_id' => ApiService::where(['name' => 'Mojaloop Hub', 'version' => 'v1.0'])->value('id'),
            ],
            [
                'name' => 'Mobile Money Operator 2',
                'api_service_id' => ApiService::where(['name' => 'Mojaloop Hub', 'version' => 'v1.0'])->value('id'),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getComponentPathsData()
    {
        return [
            [],
            [],
            [
                [
                    'target_id' => Component::where('name', 'Payer')->value('id'),
                    'simulated' => false,
                ],
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
            ],
            [
                [
                    'target_id' => Component::where('name', 'Service Provider')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => Component::where('name', 'Mojaloop')->value('id'),
                    'simulated' => true,
                ],
            ],
            [
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'simulated' => true,
                ],
            ],
            [
                [
                    'target_id' => Component::where('name', 'Mojaloop')->value('id'),
                    'simulated' => true,
                ],
            ],
        ];
    }
}
