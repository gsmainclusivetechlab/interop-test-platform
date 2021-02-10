<?php

namespace App\Imports;

use App\Http\Client\Request;
use App\Http\Client\Response;
use App\Models\{
    ApiSpec,
    Component,
    TestCase,
    TestScript,
    TestSetup,
    TestStep,
    UseCase
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TestCaseImport implements Importable
{
    /**
     * @param array $rows
     * @return TestCase
     * @throws \Throwable
     */
    public function import(array $rows): Model
    {
        return DB::transaction(function () use ($rows) {
            $useCase = UseCase::firstOrCreate([
                'name' => Arr::get($rows, 'use_case'),
            ]);
            $testCaseData = Arr::only($rows, TestCase::make()->getFillable());
            Validator::validate(
                $testCaseData,
                [
                    'slug' => [
                        Rule::unique('test_cases')->ignore(
                            Arr::get($rows, 'test_case_group_id'),
                            'test_case_group_id'
                        ),
                    ],
                ],
                [
                    'slug.unique' =>
                        'Slug should be unique for different test cases groups.',
                ]
            );

            /**
             * @var TestCase $testCase
             */
            $testCase = $useCase->testCases()->make($testCaseData);
            $testCase->saveOrFail();

            if ($componentRows = Arr::get($rows, 'components', [])) {
                $componentRows = collect($componentRows)->keyBy('slug');
                $components = Component::whereIn(
                    'slug',
                    $componentRows->keys()
                )->pluck('id', 'slug');

                $componentRows
                    ->diffKeys($components)
                    ->each(function ($componentRow, $slug) use ($testCase) {
                        $testCase
                            ->components()
                            ->create(
                                ['slug' => $slug],
                                $this->parseComponentRow($componentRow)
                            );
                    });

                $components->each(function ($id, $slug) use (
                    $testCase,
                    $componentRows
                ) {
                    $testCase
                        ->components()
                        ->attach(
                            $id,
                            $this->parseComponentRow($componentRows->get($slug))
                        );
                });
            }

            if ($testStepRows = Arr::get($rows, 'test_steps', [])) {
                foreach ($testStepRows as $testStepRow) {
                    /**
                     * @var TestStep $testStep
                     */
                    $testStep = $testCase
                        ->testSteps()
                        ->make(
                            Arr::only(
                                $testStepRow,
                                TestStep::make()->getFillable()
                            )
                        );

                    $request = Arr::get($testStepRow, 'request');
                    $response = Arr::get($testStepRow, 'response');

                    if (!Arr::exists($request, 'body')) {
                        $request['body'] = Request::EMPTY_BODY;
                    }
                    $testStep->request = $this->checkHeaders($request);

                    if (!Arr::exists($response, 'body')) {
                        $response['body'] = Response::EMPTY_BODY;
                    }
                    $testStep->response = $this->checkHeaders($response);

                    $testStep->setAttribute(
                        'source_id',
                        Component::where(
                            'slug',
                            Arr::get($testStepRow, 'source')
                        )->value('id')
                    );
                    $testStep->setAttribute(
                        'target_id',
                        Component::where(
                            'slug',
                            Arr::get($testStepRow, 'target')
                        )->value('id')
                    );
                    $testStep->setAttribute(
                        'api_spec_id',
                        ApiSpec::where(
                            'name',
                            Arr::get($testStepRow, 'api_spec')
                        )->value('id')
                    );
                    $repeat = Arr::get($testStepRow, 'repeat', []);
                    $testStep = $this->setRepeat($testStep, $repeat);
                    $testStep->saveOrFail();

                    $this->importTestSetups(
                        $testStep,
                        TestSetup::TYPE_REQUEST,
                        Arr::get($testStepRow, 'test_request_setups', [])
                    );
                    $this->importTestSetups(
                        $testStep,
                        TestSetup::TYPE_RESPONSE,
                        Arr::get($testStepRow, 'test_response_setups', [])
                    );

                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_REQUEST,
                        Arr::get($testStepRow, 'test_request_scripts', [])
                    );
                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_RESPONSE,
                        Arr::get($testStepRow, 'test_response_scripts', [])
                    );
                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_REPEAT_RESPONSE,
                        Arr::get($repeat, 'test_response_scripts', [])
                    );
                }
            }

            return $testCase;
        });
    }

    protected function parseComponentRow($componentRow): array
    {
        return [
            'component_name' => Arr::get($componentRow, 'name'),
            'component_versions' => ($versions = Arr::get(
                $componentRow,
                'versions'
            ))
                ? (array) $versions
                : [],
        ];
    }

    /**
     * @param array $input
     * @return array
     */
    protected function checkHeaders($input)
    {
        if (
            Arr::exists($input, 'headers') &&
            (!is_array($input['headers']) || empty($input['headers']))
        ) {
            $input = Arr::except($input, 'headers');
        }

        return $input;
    }

    /**
     * @param TestStep $testStep
     * @param string $type
     * @param array $rows
     * @throws \Throwable
     */
    protected function importTestSetups(TestStep $testStep, $type, array $rows)
    {
        foreach ($rows as $row) {
            /**
             * @var TestSetup $testSetup
             */
            $testSetup = $testStep
                ->testSetups()
                ->make(Arr::only($row, TestSetup::make()->getFillable()));
            $testSetup->type = $type;
            $testSetup->saveOrFail();
        }
    }

    /**
     * @param TestStep $testStep
     * @param string $type
     * @param array $rows
     * @throws \Throwable
     */
    protected function importTestScripts(TestStep $testStep, $type, array $rows)
    {
        foreach ($rows as $row) {
            /**
             * @var TestScript $testScript
             */
            $testScript = $testStep
                ->testScripts()
                ->make(Arr::only($row, TestScript::make()->getFillable()));
            $testScript->type = $type;
            $testScript->saveOrFail();
        }
    }

    /**
     * @param TestStep $testStep
     * @param array $repeat
     * @return TestStep
     * @throws \Throwable
     */
    protected function setRepeat(TestStep $testStep, $repeat)
    {
        $response = Arr::get($repeat, 'response');
        if ($response) {
            if (!Arr::exists($response, 'body')) {
                $response['body'] = Response::EMPTY_BODY;
            }
            $response['status'] = $response['status'] ?? $testStep->response->status();
            $response = $this->checkHeaders($response);
        }
        $testStep->setAttribute('repeat_max', Arr::get($repeat, 'max', 0));
        $testStep->setAttribute('repeat_count', Arr::get($repeat, 'count', 0));
        $testStep->setAttribute('repeat_condition', Arr::get($repeat, 'condition'));
        $testStep->setAttribute('repeat_response', $response);

        return $testStep;
    }
}
