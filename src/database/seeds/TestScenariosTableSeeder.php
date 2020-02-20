<?php

use App\Models\Specification;
use App\Models\TestCase;
use App\Models\TestPlatform;
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
            $scenario->platforms()->createMany(Arr::get($this->getPlatformsData(), $key, []))->each(function (TestPlatform $platform, $key) {
                $platform->connections()->attach(Arr::get($this->getPlatformsConnectionsData(), $key, []));
            });
            $scenario->suites()->createMany(Arr::get($this->getSuitesData(), $key, []))->each(function (TestSuite $suite, $key) {
                $suite->cases()->createMany(Arr::get($this->getCasesData(), $key, []))->each(function (TestCase $case, $key) {
                    $case->steps()->createMany(Arr::get($this->getStepsData(), $key, []));
                });
            });
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API v1.0 and Mojaloop Hub API v1.0',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPlatformsData()
    {
        return [
            [
                [
                    'name' => 'Payer',
                ],
                [
                    'name' => 'Service Provider',
                    'sut' => true,
                ],
                [
                    'name' => 'Mobile Money Operator 1',
                    'specification_id' => Specification::where('name', 'Mobile Money API v1.0')->value('id'),
                ],
                [
                    'name' => 'Mojaloop System',
                    'specification_id' => Specification::where('name', 'Mojaloop Hub API v1.0')->value('id'),
                ],
                [
                    'name' => 'Mobile Money Operator 2',
                    'specification_id' => Specification::where('name', 'Mojaloop Hub API v1.0')->value('id'),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPlatformsConnectionsData()
    {
        return [
            /**
             * Payer
             */
            [
                [
                    'target_id' => TestPlatform::where('name', 'Service Provider')->value('id'),
                    'simulated' => false,
                ],
            ],
            /**
             * Service Provider
             */
            [
                [
                    'target_id' => TestPlatform::where('name', 'Payer')->value('id'),
                    'simulated' => false,
                ],
                [
                    'target_id' => TestPlatform::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 1
             */
            [
                [
                    'target_id' => TestPlatform::where('name', 'Service Provider')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => TestPlatform::where('name', 'Mojaloop System')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mojaloop System
             */
            [
                [
                    'target_id' => TestPlatform::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => TestPlatform::where('name', 'Mobile Money Operator 2')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 2
             */
            [
                [
                    'target_id' => TestPlatform::where('name', 'Mojaloop System')->value('id'),
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
                    'name' => 'Merchant Initiated Flow'
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
                    'source_id' => TestPlatform::where('name', 'Service Provider')->value('id'),
                    'target_id' => TestPlatform::where('name', 'Mobile Money Operator 1')->value('id'),
                    'request_validation' => '',
                    'response_validation' => '',
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => TestPlatform::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => TestPlatform::where('name', 'Mojaloop System')->value('id'),
                    'request_validation' => '',
                    'response_validation' => '',
                ],
            ],
        ];
    }
}
