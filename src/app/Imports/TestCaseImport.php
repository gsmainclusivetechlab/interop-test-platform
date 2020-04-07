<?php

namespace App\Imports;

use App\Enums\HttpTypeEnum;
use App\Models\Scenario;
use App\Models\TestCase;
use App\Models\TestDataExample;
use App\Models\TestScript;
use App\Models\TestSetup;
use App\Models\TestStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TestCaseImport implements Importable
{
    /**
     * @var Scenario
     */
    protected $scenario;

    /**
     * TestCaseImport constructor.
     * @param Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @param array $rows
     * @return Model
     * @throws \Throwable
     */
    public function import(array $rows): Model
    {
        return DB::transaction(function () use ($rows) {
            $testCase = TestCase::make(Arr::only($rows, TestCase::make()->getFillable()));
            $testCase->setAttribute('use_case_id', $this->scenario->useCases()->where('name', Arr::get($rows, 'use_case'))->value('id'));
            $testCase->saveOrFail();

            if ($testStepRows = Arr::get($rows, 'test_steps', [])) {
                foreach ($testStepRows as $testStepRow) {
                    /**
                     * @var TestStep $testStep
                     */
                    $testStep = $testCase->testSteps()->make(Arr::only($testStepRow, TestStep::make()->getFillable()));
                    $testStep->setAttribute('source_id', $this->scenario->components()->where('name', Arr::get($testStepRow, 'source'))->value('id'));
                    $testStep->setAttribute('target_id', $this->scenario->components()->where('name', Arr::get($testStepRow, 'target'))->value('id'));
                    $testStep->saveOrFail();

                    if ($testRequestSetupRows = Arr::get($testStepRow, 'test_request_setups', [])) {
                        foreach ($testRequestSetupRows as $testRequestSetupRow) {
                            /**
                             * @var TestSetup $testRequestSetup
                             */
                            $testRequestSetup = $testStep->testSetups()->make(Arr::only($testRequestSetupRow, TestSetup::make()->getFillable()));
                            $testRequestSetup->type = HttpTypeEnum::REQUEST;
                            $testRequestSetup->saveOrFail();
                        }
                    }

                    if ($testRequestScriptRows = Arr::get($testStepRow, 'test_request_scripts', [])) {
                        foreach ($testRequestScriptRows as $testRequestScriptRow) {
                            /**
                             * @var TestScript $testRequestScript
                             */
                            $testRequestScript = $testStep->testScripts()->make(Arr::only($testRequestScriptRow, TestScript::make()->getFillable()));
                            $testRequestScript->type = HttpTypeEnum::REQUEST;
                            $testRequestScript->saveOrFail();
                        }
                    }

                    if ($testResponseSetupRows = Arr::get($testStepRow, 'test_response_setups', [])) {
                        foreach ($testResponseSetupRows as $testResponseSetupRow) {
                            /**
                             * @var TestSetup $testResponseSetup
                             */
                            $testResponseSetup = $testStep->testSetups()->make(Arr::only($testResponseSetupRow, TestSetup::make()->getFillable()));
                            $testResponseSetup->type = HttpTypeEnum::RESPONSE;
                            $testResponseSetup->saveOrFail();
                        }
                    }

                    if ($testResponseScriptRows = Arr::get($testStepRow, 'test_response_scripts', [])) {
                        foreach ($testResponseScriptRows as $testResponseScriptRow) {
                            /**
                             * @var TestScript $testResponseScript
                             */
                            $testResponseScript = $testStep->testScripts()->make(Arr::only($testResponseScriptRow, TestScript::make()->getFillable()));
                            $testResponseScript->type = HttpTypeEnum::RESPONSE;
                            $testResponseScript->saveOrFail();
                        }
                    }

                    if ($testDataExampleRow = Arr::get($testStepRow, 'data_example')) {
                        $dataExample = $testStep->testDataExample()
                            ->make(Arr::only($testDataExampleRow, TestDataExample::make()->getFillable()));
                        $dataExample->saveOrFail();
                    }
                }
            }

            return $testCase;
        });
    }
}
