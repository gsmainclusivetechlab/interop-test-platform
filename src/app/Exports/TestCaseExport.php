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

        return Yaml::dump(
            $this->arrayFilter($data),
            2,
            2,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
        );
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
     * @return array|null
     */
    protected function mapTestSteps($testSteps)
    {
        $result = [];
        foreach ($testSteps as $testStep) {
            $testScripts = $testStep->testScripts()->get();
            $testSetups = $testStep->testSetups()->get();
            $result[] = $this->arrayFilter([
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
                'request' => json_decode(
                    $testStep->getRawOriginal('request'),
                    true
                ),
                'response' => json_decode(
                    $testStep->getRawOriginal('response'),
                    true
                ),
            ]);
        }

        return $this->arrayFilter($result);
    }

    /**
     * @param Collection|TestSetup[] $testSetups
     * @param $type
     * @return array|null
     */
    protected function mapTestSetups($testSetups, $type)
    {
        $result = [];
        foreach ($testSetups as $testSetup) {
            if ($type !== $testSetup->type) {
                continue;
            }
            $result[] = $this->arrayFilter([
                'name' => $testSetup->name,
                'values' => $testSetup->values,
            ]);
        }

        return $this->arrayFilter($result);
    }

    /**
     * @param Collection|TestScript[] $testScripts
     * @param $type
     * @return array|null
     */
    protected function mapTestScripts($testScripts, $type)
    {
        $result = [];
        foreach ($testScripts as $testScript) {
            if ($type !== $testScript->type) {
                continue;
            }
            $result[] = $this->arrayFilter([
                'name' => $testScript->name,
                'rules' => $testScript->rules,
            ]);
        }

        return $this->arrayFilter($result);
    }

    /**
     * @param $array
     * @return array|null
     */
    protected function arrayFilter($array)
    {
        return empty($array)
            ? null
            : array_filter(
                $array,
                function ($value) {
                    return !is_null($value) && $value !== '';
                }
            );
    }
}
