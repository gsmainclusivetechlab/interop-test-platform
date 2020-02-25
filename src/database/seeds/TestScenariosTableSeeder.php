<?php

use App\Models\Specification;
use App\Models\TestCase;
use App\Models\TestComponent;
use App\Models\TestScenario;
use App\Models\TestSuite;
use Illuminate\Database\Seeder;

class TestScenariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $key => $data) {
            $scenario = TestScenario::create($data);
            $scenario->components()->createMany(Arr::get($this->getComponentsData(), $key))->each(function (TestComponent $component, $key) {
                $component->platform()->createMany(Arr::get($this->getPlatformsData(), $key, []));
                $component->connections()->attach(Arr::get($this->getConnectionsData(), $key, []));
            });
//            $scenario->suites()->createMany(Arr::get($this->getSuitesData(), $key, []))->each(function (TestSuite $suite, $key) {
//                $suite->cases()->createMany(Arr::get($this->getCasesData(), $key, []))->each(function (TestCase $case, $key) {
//                    $case->steps()->createMany(Arr::get($this->getStepsData(), $key, []));
//                });
//            });
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API v1.1.0 and Mojaloop FSPIOP API v1.0',
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
                [
                    'name' => 'Payer',
                ],
                [
                    'name' => 'Service Provider',
                ],
                [
                    'name' => 'Mobile Money Operator 1',
                ],
                [
                    'name' => 'Mojaloop System',
                ],
                [
                    'name' => 'Mobile Money Operator 2',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPlatformsData()
    {
        return [
            [],
            [],
            [
                [
                    'specification_id' => Specification::where('name', 'Mobile Money API v1.1.0')->firstOrFail()->value('id'),
                    'server' => 'http://gsma-itp-mmo-api.develop.s8.jc',
                ],
            ],
            [
                [
                    'specification_id' => Specification::where('name', 'Mojaloop FSPIOP API v1.0')->firstOrFail()->value('id'),
                    'server' => 'http://mojaloop.s9.jc',
                ],
            ],
            [
                [
                    'specification_id' => Specification::where('name', 'Mojaloop FSPIOP API v1.0')->firstOrFail()->value('id'),
                    'server' => 'http://moja-simulator.develop.s8.jc',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getConnectionsData()
    {
        return [
            /**
             * Payer
             */
            [
                [
                    'target_id' => TestComponent::where('name', 'Service Provider')->value('id'),
                    'simulated' => false,
                ],
            ],
            /**
             * Service Provider
             */
            [
                [
                    'target_id' => TestComponent::where('name', 'Payer')->value('id'),
                    'simulated' => false,
                ],
                [
                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 1
             */
            [
                [
                    'target_id' => TestComponent::where('name', 'Service Provider')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mojaloop System
             */
            [
                [
                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 2')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 2
             */
            [
                [
                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
                    'simulated' => true,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSuitesData()
    {
        return [
            [
                [
                    'name' => 'Merchant-Initiated Merchant Payment',
                    'description' => 'A Merchant-Initiated Merchant Payment is typically a receive amount, where the Payer FSP is not disclosing any fees to the Payee FSP. Please refer to 5.1.6.8 in "Open API for FSP Interoperability Specification" for more details.',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getCasesData()
    {
        return [
            [
                [
                    'name' => 'Authorized Transaction',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getStepsData()
    {
        return [
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'source_id' => TestComponent::where('name', 'Service Provider')->value('id'),
                    'target_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => TestComponent::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => TestComponent::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [],
                ],
            ],
        ];
    }
}
