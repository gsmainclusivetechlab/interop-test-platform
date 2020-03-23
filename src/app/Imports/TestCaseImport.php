<?php

namespace App\Imports;

use App\Models\Scenario;
use App\Models\TestCase;
use App\Models\TestScript;
use App\Models\TestResponseScript;
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

                    if ($testRequestScriptsRows = Arr::get($testStepRow, 'test_request_scripts', [])) {
                        foreach ($testRequestScriptsRows as $testRequestScriptRow) {
                            /**
                             * @var TestScript $testRequestScript
                             */
                            $testRequestScript = $testStep->testRequestScripts()->make(Arr::only($testRequestScriptRow, TestScript::make()->getFillable()));
                            $testRequestScript->saveOrFail();
                        }
                    }

                    if ($testResponseScriptRows = Arr::get($testStepRow, 'test_response_scripts', [])) {
                        foreach ($testResponseScriptRows as $testResponseScriptRow) {
                            /**
                             * @var TestResponseScript $testResponseScript
                             */
                            $testResponseScript = $testStep->testResponseScripts()->make(Arr::only($testResponseScriptRow, TestResponseScript::make()->getFillable()));
                            $testResponseScript->saveOrFail();
                        }
                    }
                }
            }

            return $testCase;
        });
    }
}
