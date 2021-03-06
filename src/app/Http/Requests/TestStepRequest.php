<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Models\{TestCase, TestScript, TestSetup, TestStep};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TestStepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'source_id' => ['required', 'exists:components,id'],
            'target_id' => ['required', 'exists:components,id'],
            'api_spec_id' => ['nullable', 'exists:api_specs,id'],
            'path' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'pattern' => ['required', 'string', 'max:255'],
            'trigger' => ['nullable', 'array'],
            'request' => ['required', 'array'],
            'request.uri' => ['required', 'string'],
            'response' => ['required', 'array'],
            'response.status' => ['required'],
            'mtls' => ['required', 'boolean'],
            // test scripts
            'test.scripts.request' => ['nullable', 'array'],
            'test.scripts.request.*.name' => ['required', 'string', 'max:255'],
            'test.scripts.request.*.rules' => ['required', 'array'],
            'test.scripts.response' => ['nullable', 'array'],
            'test.scripts.response.*.name' => ['required', 'string', 'max:255'],
            'test.scripts.response.*.rules' => ['required', 'array'],
            /*test setups
            'test.setups.request' => ['nullable', 'array'],
            'test.setups.request.*.name' => ['required', 'string', 'max:255'],
            'test.setups.request.*.values' => ['required', 'array'],
            'test.setups.response' => ['nullable', 'array'],
            'test.setups.response.*.name' => ['required', 'string', 'max:255'],
            'test.setups.response.*.values' => ['required', 'array'],*/
            // callback
            'callback' => ['required', 'array'],
            'callback.method' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(function () {
                    return $this->input('callback.path') ||
                        $this->input('callback.name');
                }),
            ],
            'callback.path' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(function () {
                    return $this->input('callback.method') ||
                        $this->input('callback.name');
                }),
            ],
            'callback.name' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(function () {
                    return $this->input('callback.method') ||
                        $this->input('callback.path');
                }),
            ],
            // repeat
            'repeat' => ['required', 'array'],
            'repeat.max' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $count = $this->input('repeat.count', 0);
                    if ($count != 0 && $count >= $value) {
                        $fail(__("Must be greater than $count."));
                    }
                },
            ],
            'repeat.count' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $max = $this->input('repeat.max', 0);
                    if ($value != 0 && $max <= $value) {
                        $fail(__("May not be greater than $max."));
                    }
                },
            ],
            'repeat.condition' => [
                'nullable',
                'array',
                Rule::requiredIf(function () {
                    return $this->input('repeat.max', 0) > 0;
                }),
            ],
            'repeat.response' => ['nullable', 'array'],
            'repeat.response.status' => [
                'nullable',
                Rule::requiredIf(function () {
                    return $this->input('repeat.count', 0) > 0;
                }),
            ],
            'test.scripts.repeat_response' => ['nullable', 'array'],
            'test.scripts.repeat_response.*.name' => [
                'required',
                'string',
                'max:255',
            ],
            'test.scripts.repeat_response.*.rules' => ['required', 'array'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            '*.required' => __('Field is required.'),
            'request.uri.required' => __('Field is required.'),
            'response.status.required' => __('Field is required.'),
            'repeat.*.min' => __('Must be at least 0.'),
            'repeat.condition.required' => __('Field is required.'),
            'repeat.response.status.required' => __('Field is required.'),
            'test.*.*.*.*.required' => __('Field is required.'),
            'test.*.*.*.name.max' => __(
                'The name may not be greater than 255 characters.'
            ),
            'callback.*.required' => __('Field is required.'),
        ];
    }

    /**
     * @param TestCase $testCase
     * @return mixed
     * @throws \Throwable
     */
    public function createTestStep(TestCase $testCase)
    {
        return DB::transaction(function () use ($testCase) {
            /**
             * @var TestStep $testStep
             */
            $testStep = $testCase->testSteps()->make($this->getFillableData());
            $testStep = $this->setAttributes($testStep);
            $testStep->saveOrFail();

            /*$this->createTestSetups(
                $testStep,
                TestSetup::TYPE_REQUEST,
                Arr::get($this->input('test.setups'), 'request', [])
            );
            $this->createTestSetups(
                $testStep,
                TestSetup::TYPE_RESPONSE,
                Arr::get($this->input('test.setups'), 'response', [])
            );*/

            $this->createTestScripts(
                $testStep,
                TestScript::TYPE_REQUEST,
                Arr::get($this->input('test.scripts'), 'request', [])
            );
            $this->createTestScripts(
                $testStep,
                TestScript::TYPE_RESPONSE,
                Arr::get($this->input('test.scripts'), 'response', [])
            );
            $this->createTestScripts(
                $testStep,
                TestScript::TYPE_REPEAT_RESPONSE,
                Arr::get($this->input('test.scripts'), 'repeat_response', [])
            );
        });
    }

    /**
     * @param TestStep $testStep
     * @return mixed
     * @throws \Throwable
     */
    public function updateTestStep(TestStep $testStep)
    {
        return DB::transaction(function () use ($testStep) {
            $testStep = $this->setAttributes($testStep);
            $testStep->update($this->getFillableData());

            /*$this->updateTestSetups(
                $testStep,
                TestSetup::TYPE_REQUEST,
                Arr::get($this->input('test.setups'), 'request', [])
            );
            $this->updateTestSetups(
                $testStep,
                TestSetup::TYPE_RESPONSE,
                Arr::get($this->input('test.setups'), 'response', [])
            );*/

            $this->updateTestScripts(
                $testStep,
                TestScript::TYPE_REQUEST,
                Arr::get($this->input('test.scripts'), 'request', [])
            );
            $this->updateTestScripts(
                $testStep,
                TestScript::TYPE_RESPONSE,
                Arr::get($this->input('test.scripts'), 'response', [])
            );
            $this->updateTestScripts(
                $testStep,
                TestScript::TYPE_REPEAT_RESPONSE,
                Arr::get($this->input('test.scripts'), 'repeat_response', [])
            );
        });
    }

    /**
     * @param TestStep $testStep
     * @return TestStep
     */
    protected function setAttributes(TestStep $testStep)
    {
        $testStep->setAttribute('source_id', $this->input('source_id'));
        $testStep->setAttribute('target_id', $this->input('target_id'));
        $testStep->setAttribute('api_spec_id', $this->input('api_spec_id'));

        return $testStep;
    }

    /**
     * @return array|mixed
     */
    protected function getFillableData()
    {
        return array_merge(
            Arr::only($this->input(), TestStep::make()->getFillable()),
            [
                'request' => $this->mapTestStepRequest(),
                'response' => $this->mapTestStepResponse(),
                'repeat_max' => $this->input('repeat.max', 0),
                'repeat_count' => $this->input('repeat.count', 0),
                'repeat_condition' => $this->input('repeat.condition'),
                'callback_origin_path' => $this->input('callback.path'),
                'callback_origin_method' => $this->input('callback.method'),
                'callback_name' => $this->input('callback.name'),
            ],
            Arr::get($this->input('repeat.response'), 'status') ?
                ['repeat_response' => $this->mapTestStepResponse('repeat.')] :
                []
        );
    }

    /**
     * @return array|mixed
     */
    protected function mapTestStepRequest()
    {
        $request = $this->input('request');
        $request['method'] = $this->input('method');

        return $this->checkHeaders($request);
    }

    /**
     * @param string $tag
     * @return array|mixed
     */
    protected function mapTestStepResponse($tag = '')
    {
        $response = $this->input($tag . 'response');

        return $this->checkHeaders($response);
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
    protected function createTestSetups(TestStep $testStep, $type, array $rows)
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
    protected function createTestScripts(TestStep $testStep, $type, array $rows)
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
     * @param string $type
     * @param array $rows
     * @throws \Throwable
     */
    protected function updateTestSetups(TestStep $testStep, $type, array $rows)
    {
        $keepIds = [];
        foreach ($rows as $row) {
            if (!empty($row['id'])) {
                $keepIds[] = $row['id'];

                $testStep
                    ->testSetups()
                    ->whereKey($row['id'])
                    ->firstOrFail()
                    ->update(Arr::only($row, TestSetup::make()->getFillable()));
            } else {
                /**
                 * @var TestSetup $testSetup
                 */
                $testSetup = $testStep
                    ->testSetups()
                    ->make(Arr::only($row, TestSetup::make()->getFillable()));
                $testSetup->type = $type;
                $testSetup->saveOrFail();
                $keepIds[] = $testSetup->id;
            }
        }

        $testStep
            ->testSetups()
            ->whereKeyNot($keepIds)
            ->where('type', $type)
            ->each(function ($testSetup) {
                $testSetup->delete();
            });
    }

    /**
     * @param TestStep $testStep
     * @param string $type
     * @param array $rows
     * @throws \Throwable
     */
    protected function updateTestScripts(TestStep $testStep, $type, array $rows)
    {
        $keepIds = [];
        foreach ($rows as $row) {
            if (!empty($row['id'])) {
                $keepIds[] = $row['id'];

                $testStep
                    ->testScripts()
                    ->whereKey($row['id'])
                    ->firstOrFail()
                    ->update(
                        Arr::only($row, TestScript::make()->getFillable())
                    );
            } else {
                /**
                 * @var TestScript $testScript
                 */
                $testScript = $testStep
                    ->testScripts()
                    ->make(Arr::only($row, TestScript::make()->getFillable()));
                $testScript->type = $type;
                $testScript->saveOrFail();
                $keepIds[] = $testScript->id;
            }
        }

        $testStep
            ->testScripts()
            ->whereKeyNot($keepIds)
            ->where('type', $type)
            ->each(function ($testSetup) {
                $testSetup->delete();
            });
    }
}
