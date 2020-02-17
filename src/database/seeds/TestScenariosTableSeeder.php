<?php

use App\Models\Specification;
use App\Models\TestCase;
use App\Models\TestPlatform;
use App\Models\TestPlatformConnection;
use App\Models\TestScenario;
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
            $scenario->platforms()->createMany(Arr::get($this->getPlatformsData(), $key, []));
            $scenario->platformsConnections()->createMany(Arr::get($this->getPlatformsConnectionsData(), $key, []));
            $scenario->operations()->createMany(Arr::get($this->getOperationsData(), $key, []))->each(function ($operation, $key) {
                $operation->steps()->createMany(Arr::get($this->getOperationsStepsData(), $key, []));
                $operation->cases()->createMany(Arr::get($this->getOperationsCasesData(), $key, []))->each(function ($case, $key) {
                    $case->steps()->createMany(Arr::get($this->getOperationsCasesStepsData(), $key, []));
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
                    'position' => 1,
                ],
                [
                    'name' => 'Service Provider',
                    'position' => 2,
                ],
                [
                    'name' => 'Mobile Money Operator 1',
                    'specification_id' => Specification::whereName('Mobile Money API v1.0')->value('id'),
                    'position' => 3,
                ],
                [
                    'name' => 'Mojaloop System',
                    'specification_id' => Specification::whereName('Mojaloop Hub API v1.0')->value('id'),
                    'position' => 4,
                ],
                [
                    'name' => 'Mobile Money Operator 2',
                    'specification_id' => Specification::whereName('Mojaloop Hub API v1.0')->value('id'),
                    'position' => 5,
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
            [
                [
                    'source_id' => TestPlatform::whereName('Payer')->value('id'),
                    'target_id' => TestPlatform::whereName('Service Provider')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_NOT_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Service Provider')->value('id'),
                    'target_id' => TestPlatform::whereName('Payer')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_NOT_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Service Provider')->value('id'),
                    'target_id' => TestPlatform::whereName('Mobile Money Operator 1')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Mobile Money Operator 1')->value('id'),
                    'target_id' => TestPlatform::whereName('Service Provider')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Mobile Money Operator 1')->value('id'),
                    'target_id' => TestPlatform::whereName('Mojaloop System')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Mojaloop System')->value('id'),
                    'target_id' => TestPlatform::whereName('Mobile Money Operator 1')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Mojaloop System')->value('id'),
                    'target_id' => TestPlatform::whereName('Mobile Money Operator 2')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
                [
                    'source_id' => TestPlatform::whereName('Mobile Money Operator 2')->value('id'),
                    'target_id' => TestPlatform::whereName('Mojaloop System')->value('id'),
                    'connection' => TestPlatformConnection::CONNECTION_SIMULATED,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getOperationsData()
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
    public function getOperationsStepsData()
    {
        return [
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'position' => 1,
                    'connection_id' => TestPlatformConnection::whereHas('source', function ($query) {
                        $query->whereName('Service Provider');
                    })->whereHas('target', function ($query) {
                        $query->whereName('Mobile Money Operator 1');
                    })->value('id'),
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'position' => 2,
                    'connection_id' => TestPlatformConnection::whereHas('source', function ($query) {
                        $query->whereName('Mobile Money Operator 1');
                    })->whereHas('target', function ($query) {
                        $query->whereName('Mojaloop System');
                    })->value('id'),
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'position' => 3,
                    'connection_id' => TestPlatformConnection::whereHas('source', function ($query) {
                        $query->whereName('Mojaloop System');
                    })->whereHas('target', function ($query) {
                        $query->whereName('Mobile Money Operator 2');
                    })->value('id'),
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'PUT',
                    'position' => 4,
                    'connection_id' => TestPlatformConnection::whereHas('source', function ($query) {
                        $query->whereName('Mobile Money Operator 2');
                    })->whereHas('target', function ($query) {
                        $query->whereName('Mojaloop System');
                    })->value('id'),
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'PUT',
                    'position' => 5,
                    'connection_id' => TestPlatformConnection::whereHas('source', function ($query) {
                        $query->whereName('Mojaloop System');
                    })->whereHas('target', function ($query) {
                        $query->whereName('Mobile Money Operator 1');
                    })->value('id'),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getOperationsCasesData()
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
    public function getOperationsCasesStepsData()
    {
        return [
            [
                [
                    'step_id' => 1,
                    'request_validation' => '',
                    'response_validation' => '',
                    'position' => 1,
                ],
                [
                    'step_id' => 2,
                    'request_validation' => '',
                    'response_validation' => '',
                    'position' => 2,
                ],
            ],
        ];
    }
}
