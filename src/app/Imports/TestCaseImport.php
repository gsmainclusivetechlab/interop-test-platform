<?php

namespace App\Imports;

use App\Models\ApiScheme;
use App\Models\Component;
use App\Models\TestCase;
use App\Models\TestScript;
use App\Models\TestSetup;
use App\Models\TestStep;
use App\Models\UseCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TestCaseImport implements Importable
{
    /**
     * @var UseCase
     */
    protected $useCase;

    /**
     * @param UseCase $useCase
     */
    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * @param array $rows
     * @return Model
     * @throws \Throwable
     */
    public function import(array $rows): Model
    {
        return DB::transaction(function () use ($rows) {
            $testCase = $this->useCase->testCases()->make(Arr::only($rows, TestCase::make()->getFillable()));
            $testCase->saveOrFail();

            if ($testStepRows = Arr::get($rows, 'test_steps', [])) {
                foreach ($testStepRows as $testStepRow) {
                    /**
                     * @var TestStep $testStep
                     */
                    $testStep = $testCase->testSteps()->make(Arr::only($testStepRow, TestStep::make()->getFillable()));
                    $testStep->setAttribute('source_id', Component::where('name', Arr::get($testStepRow, 'source'))->value('id'));
                    $testStep->setAttribute('target_id', Component::where('name', Arr::get($testStepRow, 'target'))->value('id'));
//                    $testStep->setAttribute('api_scheme_id', ApiScheme::where('name', Arr::get($testStepRow, 'api_scheme'))->value('id'));
                    $testStep->saveOrFail();

//                    if ($testRequestSetupRows = Arr::get($testStepRow, 'test_request_setups', [])) {
//                        foreach ($testRequestSetupRows as $testRequestSetupRow) {
//                            /**
//                             * @var TestSetup $testRequestSetup
//                             */
//                            $testRequestSetup = $testStep->testSetups()->make(Arr::only($testRequestSetupRow, TestSetup::make()->getFillable()));
//                            $testRequestSetup->type = TestSetup::TYPE_REQUEST;
//                            $testRequestSetup->saveOrFail();
//                        }
//                    }
//
//                    if ($testRequestScriptRows = Arr::get($testStepRow, 'test_request_scripts', [])) {
//                        foreach ($testRequestScriptRows as $testRequestScriptRow) {
//                            /**
//                             * @var TestScript $testRequestScript
//                             */
//                            $testRequestScript = $testStep->testScripts()->make(Arr::only($testRequestScriptRow, TestScript::make()->getFillable()));
//                            $testRequestScript->type = TestScript::TYPE_REQUEST;
//                            $testRequestScript->saveOrFail();
//                        }
//                    }
//
//                    if ($testResponseSetupRows = Arr::get($testStepRow, 'test_response_setups', [])) {
//                        foreach ($testResponseSetupRows as $testResponseSetupRow) {
//                            /**
//                             * @var TestSetup $testResponseSetup
//                             */
//                            $testResponseSetup = $testStep->testSetups()->make(Arr::only($testResponseSetupRow, TestSetup::make()->getFillable()));
//                            $testResponseSetup->type = TestSetup::TYPE_RESPONSE;
//                            $testResponseSetup->saveOrFail();
//                        }
//                    }
//
//                    if ($testResponseScriptRows = Arr::get($testStepRow, 'test_response_scripts', [])) {
//                        foreach ($testResponseScriptRows as $testResponseScriptRow) {
//                            /**
//                             * @var TestScript $testResponseScript
//                             */
//                            $testResponseScript = $testStep->testScripts()->make(Arr::only($testResponseScriptRow, TestScript::make()->getFillable()));
//                            $testResponseScript->type = TestScript::TYPE_RESPONSE;
//                            $testResponseScript->saveOrFail();
//                        }
//                    }
                }
            }

            return $testCase;
        });
    }
}
