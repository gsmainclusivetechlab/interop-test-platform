<?php

namespace App\Imports;

use App\Enums\HttpMethod;
use App\Enums\HttpStatus;
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
use Illuminate\Validation\ValidationException;

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
            Validator::validate(
                $rows,
                $this->testCaseRules($rows),
                $this->testCaseMessages()
            );

            $useCase = UseCase::firstOrCreate([
                'name' => Arr::get($rows, 'use_case'),
            ]);
            /**
             * @var TestCase $testCase
             */
            $testCase = $useCase
                ->testCases()
                ->make(Arr::only($rows, TestCase::make()->getFillable()));
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
                $this->validateTestSteps($testStepRows);
                foreach ($testStepRows as $key => $testStepRow) {
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
                    $testStep = $this->setCallback(
                        $testStep,
                        Arr::get($testStepRow, 'callback', [])
                    );
                    $testStep->saveOrFail();

                    $this->importTestSetups(
                        $testStep,
                        TestSetup::TYPE_REQUEST,
                        Arr::get($testStepRow, 'test_request_setups', []) ?: []
                    );
                    $this->importTestSetups(
                        $testStep,
                        TestSetup::TYPE_RESPONSE,
                        Arr::get($testStepRow, 'test_response_setups', []) ?: []
                    );

                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_REQUEST,
                        Arr::get($testStepRow, 'test_request_scripts', []) ?: []
                    );
                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_RESPONSE,
                        Arr::get($testStepRow, 'test_response_scripts', []) ?: []
                    );
                    $this->importTestScripts(
                        $testStep,
                        TestScript::TYPE_REPEAT_RESPONSE,
                        Arr::get($repeat, 'test_response_scripts', []) ?: []
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
            $response = $this->checkHeaders($response);
        }
        $testStep->fill([
            'repeat_max' => Arr::get($repeat, 'max', 0),
            'repeat_count' => Arr::get($repeat, 'count', 0),
            'repeat_condition' => Arr::get($repeat, 'condition'),
            'repeat_response' => $response,
        ]);

        return $testStep;
    }

    /**
     * @param TestStep $testStep
     * @param $callback
     * @return TestStep
     */
    protected function setCallback(TestStep $testStep, $callback)
    {
        $testStep->fill([
            'callback_origin_method' => Arr::get($callback, 'origin_method'),
            'callback_origin_path' => Arr::get($callback, 'origin_path'),
            'callback_name' => Arr::get($callback, 'name'),
        ]);

        return $testStep;
    }

    /**
     * @param $rows
     */
    protected function validateTestSteps($rows)
    {
        $errors = '<ol>';
        $hasErrors = false;
        foreach ($rows as $key => $testStepRow) {
            $key++;
            $testStepValidator = Validator::make(
                $testStepRow,
                $this->testStepRules($testStepRow, $key),
                $this->testStepMessages()
            );
            if ($testStepValidator->fails()) {
                $hasErrors = true;
                $errors .= "<li><b>Test step $key:</b><ul>";
                foreach ($testStepValidator->errors()->all() as $message) {
                    $errors .= "<li>$message</li>";
                }
                $errors .= '</ul></li>';
            }
        }
        $errors .= '</ol>';

        if ($hasErrors) {
            throw ValidationException::withMessages([$errors]);
        }
    }

    protected function testCaseRules($rows): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'use_case' => ['required', 'string', 'max:255'],
            'behavior' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_keys(TestCase::getBehaviorNames())),
            ],
            'description' => ['string'],
            'precondition' => ['string'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('test_cases')->ignore(
                    Arr::get($rows, 'test_case_group_id'),
                    'test_case_group_id'
                ),
            ],
            'components' => ['nullable', 'array'],
            'components.*.name' => ['required', 'string', 'max:255'],
            'components.*.slug' => ['required', 'string', 'max:255'],
            'components.*.versions' => ['nullable', 'array'],
            'components.*.versions.*' => ['required', 'string'],
        ];
    }

    /**
     * @param $rows
     * @param $step
     * @return array
     */
    protected function testStepRules($rows, $step): array
    {
        return [
            'source' => ['required', 'exists:components,slug'],
            'target' => ['required', 'exists:components,slug'],
            'api_spec' => ['nullable', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255'],
            'method' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_keys(HttpMethod::list())),
            ],
            'pattern' => ['required', 'string', 'max:255'],
            'trigger' => ['nullable', 'array'],
            'mtls' => ['nullable', 'boolean'],
            'request' => ['required', 'array'],
            'request.uri' => ['required', 'string'],
            'request.method' => [
                'required',
                'string',
                Rule::in(array_keys(HttpMethod::list())),
            ],
            'response' => ['required', 'array'],
            'response.status' => [
                'required',
                Rule::in(array_keys(HttpStatus::list())),
            ],
            // test scripts
            'test_request_scripts' => ['nullable', 'array'],
            'test_request_scripts.*.name' => ['required', 'string', 'max:255'],
            'test_request_scripts.*.rules' => ['required', 'array'],
            'test_response_scripts' => ['nullable', 'array'],
            'test_response_scripts.*.name' => ['required', 'string', 'max:255'],
            'test_response_scripts.*.rules' => ['required', 'array'],
            //repeats
            'repeat' => ['nullable', 'array'],
            'repeat.max' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($rows, $step) {
                    $count = Arr::get($rows, 'repeat.count', 0);
                    if ($count != 0 && $count >= $value) {
                        $fail(
                            __(
                                'The repeat max must be greater than count (:count). On Test Step :step.',
                                ['count' => $count, 'step' => $step]
                            )
                        );
                    }
                },
                Rule::requiredIf(function () use ($rows) {
                    return !empty(Arr::get($rows, 'repeat.count'));
                }),
            ],
            'repeat.count' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($rows, $step) {
                    $max = Arr::get($rows, 'repeat.max', 0);
                    if ($value != 0 && $max <= $value) {
                        $fail(
                            __(
                                'The repeat count may not be greater than max (:max). On Test Step :step.',
                                ['max' => $max, 'step' => $step]
                            )
                        );
                    }
                },
            ],
            'repeat.condition' => [
                'nullable',
                'array',
                Rule::requiredIf(function () use ($rows) {
                    return Arr::get($rows, 'repeat.max', 0) > 0;
                }),
            ],
            'repeat.response' => [
                'nullable',
                'array',
                Rule::requiredIf(function () use ($rows) {
                    return Arr::get($rows, 'repeat.count', 0) > 0;
                }),
            ],
            'repeat.response.status' => [
                'nullable',
                Rule::in(array_keys(HttpStatus::list())),
                Rule::requiredIf(function () use ($rows) {
                    return Arr::get($rows, 'repeat.count', 0) > 0;
                }),
            ],
            'repeat.test_response_scripts' => ['nullable', 'array'],
            'repeat.test_response_scripts.*.name' => [
                'required',
                'string',
                'max:255',
            ],
            'repeat.test_response_scripts.*.rules' => ['required', 'array'],
        ];
    }

    protected function testCaseMessages(): array
    {
        return [
            'slug.unique' => __(
                'The slug should be unique for different test cases groups.'
            ),
            'behavior.in' => __('The behavior must be in: :behaviour.', [
                'behaviour' => implode(
                    ', ',
                    array_keys(TestCase::getBehaviorNames())
                ),
            ]),
        ];
    }

    protected function testStepMessages(): array
    {
        return [
            'source.exists' => __('The source component does not exist.'),
            'target.exists' => __('The target component does not exist.'),
            'method.in' => __('The method must be in: :methods.', [
                'methods' => implode(', ', array_keys(HttpMethod::list())),
            ]),

            // request
            'request.uri.required' => __('The request uri field is required.'),
            'request.method.required' => __(
                'The request method field is required.'
            ),
            'request.method.in' => __(
                'The request method must be in: :methods.',
                [
                    'methods' => implode(', ', array_keys(HttpMethod::list())),
                ]
            ),

            // response
            'response.status.required' => __(
                'The response status field is required.'
            ),
            'response.status.in' => __('The response status is invalid.'),

            // repeats
            'repeat.max.required' => __('The repeat max field is required.'),
            'repeat.max.integer' => __('The repeat max must be an integer.'),
            'repeat.max.min' => __('The repeat max must be at least 0.'),
            'repeat.count.integer' => __(
                'The repeat count must be an integer.'
            ),
            'repeat.count.min' => __('The repeat count must be at least 0.'),
            'repeat.condition.required' => __(
                'The repeat condition field is required.'
            ),
            'repeat.response.required' => __(
                'The repeat response field is required.'
            ),
            'repeat.response.status.required' => __(
                'The repeat response status field is required.'
            ),
            'repeat.response.status.in' => __(
                'The repeat response status field is invalid.'
            ),
        ];
    }
}
