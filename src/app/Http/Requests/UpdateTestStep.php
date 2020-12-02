<?php

namespace App\Http\Requests;

use App\Models\TestCase;
use App\Models\TestScript;
use App\Models\TestSetup;
use App\Models\TestStep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateTestStep extends FormRequest
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
            'request' => ['nullable', 'array'],
            'response' => ['required', 'array'],
            'response.status' => ['required'],
            // test scripts
            'test.scripts.request' => ['nullable', 'array'],
            'test.scripts.request.*.name' => ['required', 'string', 'max:255'],
            'test.scripts.request.*.rules' => ['required', 'array'],
            'test.scripts.response' => ['nullable', 'array'],
            'test.scripts.response.*.name' => ['required', 'string', 'max:255'],
            'test.scripts.response.*.rules' => ['required', 'array'],
            //test setups
            'test.setups.request' => ['nullable', 'array'],
            'test.setups.request.*.name' => ['required', 'string', 'max:255'],
            'test.setups.request.*.values' => ['required', 'array'],
            'test.setups.response' => ['nullable', 'array'],
            'test.setups.response.*.name' => ['required', 'string', 'max:255'],
            'test.setups.response.*.values' => ['required', 'array'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            '*.required' => __('Field is required.'),
            'response.status.required' => __('Field is required.'),
            'test.*.*.*.*.required' => __('Field is required.'),
            'test.*.*.*.name.max' => __('The name may not be greater than 255 characters.'),
        ];
    }

    /**
     * @param TestStep $testStep
     * @return mixed
     * @throws \Throwable
     */
    public function updateTestStep(TestStep $testStep)
    {
        return DB::transaction(function () use ($testStep) {
            $testStep->setRawAttributes(
                    Arr::only(
                        $this->input(),
                        TestStep::make()->getFillable()
                    )
                );dd($testStep);
            $testStep->setAttribute(
                'request',
                $this->mapTestStepRequest()
            );
            $testStep->setAttribute(
                'response',
                $this->mapTestStepResponse()
            );
            $testStep->setAttribute(
                'source_id',
                $this->input('source_id')
            );
            $testStep->setAttribute(
                'target_id',
                $this->input('target_id')
            );
            $testStep->setAttribute(
                'api_spec_id',
                $this->input('api_spec_id')
            );
            $testStep->saveOrFail();

            $this->updateTestSetups(
                $testStep,
                TestSetup::TYPE_REQUEST,
                Arr::get($this->input('test.setups'), 'request', [])
            );
            $this->updateTestSetups(
                $testStep,
                TestSetup::TYPE_RESPONSE,
                Arr::get($this->input('test.setups'), 'response', [])
            );

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
        });
    }

    protected function mapTestStepRequest()
    {
        $request = $this->input('request');dd($request);
        $request['method'] = $this->input('method');
        $request['uri'] = $this->input('path');

        return array_filter($request);
    }

    protected function mapTestStepResponse()
    {
        $request = $this->input('response');

        return array_filter($request);
    }

    /**
     * @param TestStep $testStep
     * @param string $type
     * @param array $rows
     * @throws \Throwable
     */
    protected function updateTestSetups(TestStep $testStep, $type, array $rows)
    {
        $testSetups = $testStep
            ->testSetups()
            ->where('type', $type)
            ->pluck('id');
        $remove = 0;
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
    protected function updateTestScripts(TestStep $testStep, $type, array $rows)
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
}
