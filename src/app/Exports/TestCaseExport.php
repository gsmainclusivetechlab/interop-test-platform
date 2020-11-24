<?php

namespace App\Exports;

use App\Models\TestCase;
use App\Models\TestScript;
use App\Models\TestSetup;
use App\Models\TestStep;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

class TestCaseExport implements Exportable
{
    /**
     * @param Model $testCase
     * @return string
     * @throws \Throwable
     */
    public function export($testCase): string
    {
        $data = $this->mapTestCase($testCase);

        return Yaml::dump($data);
    }

    /**
     * @param TestCase|Model $testCase
     * @return array
     */
    protected function mapTestCase(TestCase $testCase): array
    {
        return [
            'name' => $testCase->name,
            'slug' => $testCase->slug,
            'use_case' => $testCase->useCase->name,
            'behavior' => $testCase->behavior,
            'description' => $testCase->description,
            'precondition' => $testCase->precondition,
            'components' => $testCase->components()
                ->pluck('name')
                ->toArray(),
            'test_steps' => $this->mapTestSteps($testCase->testSteps()->get()),
        ];
    }

    /**
     * @param Collection|TestStep[] $testSteps
     * @return array
     */
    protected function mapTestSteps($testSteps): array
    {
        $result = [];
        foreach ($testSteps as $testStep) {
            $testScripts = $testStep->testScripts()->get();
            $testSetups = $testStep->testSetups()->get();
            $result[] = [
                'path' => $testStep->path,
                'pattern' => $testStep->pattern,
                'method' => $testStep->method,
                'source' => $testStep->source()
                    ->first()
                    ->name,
                'target' => $testStep->target()
                    ->first()
                    ->name,
                'api_spec' => $testStep->apiSpec()->exists()
                    ? $testStep->apiSpec()
                        ->first()
                        ->name
                    : null,
                'trigger' => $testStep->trigger,
                'test_request_setups' => $this->mapTestSetups(
                    $testSetups,
                    TestSetup::TYPE_REQUEST
                ),
                'test_response_setups' => $this->mapTestSetups(
                    $testSetups,
                    TestSetup::TYPE_RESPONSE
                ),
                'test_request_scripts' => $this->mapTestScripts(
                    $testScripts,
                    TestScript::TYPE_REQUEST
                ),
                'test_response_scripts' => $this->mapTestScripts(
                    $testScripts,
                    TestScript::TYPE_RESPONSE
                ),
                'request' => array_filter(optional($testStep->request)->toArray()),
                'response' => array_filter(optional($testStep->response)->toArray()),
            ];
        }

        return $result;
    }

    /**
     * @param Collection|TestSetup[] $testSetups
     * @param $type
     * @return array
     */
    protected function mapTestSetups($testSetups, $type): array
    {
        $result = [];
        foreach ($testSetups as $testSetup) {
            if ($type !== $testSetup->type) {
                continue;
            }
            $result[] = [
                'name' => $testSetup->name,
                'values' => $testSetup->values,
            ];
        }

        return $result;
    }

    /**
     * @param Collection|TestScript[] $testScripts
     * @param $type
     * @return array
     */
    protected function mapTestScripts($testScripts, $type): array
    {
        $result = [];
        foreach ($testScripts as $testScript) {
            if ($type !== $testScript->type) {
                continue;
            }
            $result[] = [
                'name' => $testScript->name,
                'rules' => $testScript->rules,
            ];
        }

        return $result;
    }
}
