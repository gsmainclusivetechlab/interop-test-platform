<?php

namespace App\Exports;

use App\Models\{Component, TestCase, TestScript, TestSetup, TestStep};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Yaml\Yaml;

class TestCaseExport implements Exportable
{
    protected $requestOrder = [
        'method',
        'uri',
        'delay',
        'path',
        'headers',
        'body',
        'jws',
    ];

    protected $responseOrder = ['status', 'delay', 'headers', 'body', 'jws'];

    /**
     * @param Model $testCase
     * @return string
     * @throws \Throwable
     */
    public function export($testCase): string
    {
        $data = $this->getArray($testCase);

        return Yaml::dump(
            $data,
            $this->getInline($data),
            2,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
        );
    }

    /**
     * @param array $array
     * @return int
     */
    protected function getInline(array $array): int
    {
        $depth = [0];
        foreach ($array as $item) {
            if (is_array($item)) {
                $depth[] = $this->getInline($item);
            }
        }

        return 1 + max($depth);
    }

    /**
     * @param Model $testCase
     * @return array
     * @throws \Throwable
     */
    public function getArray($testCase): array
    {
        return $this->arrayFilter($this->mapTestCase($testCase));
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
            'components' => $testCase
                ->components()
                ->get()
                ->map(function (Component $component) {
                    $versions = $component->pivot->component_versions
                        ? [
                            'versions' => $component->pivot->component_versions,
                        ]
                        : [];

                    return array_merge(
                        $component->only(['name', 'slug']),
                        $versions
                    );
                })
                ->toArray(),
            'test_steps' => $this->mapTestSteps($testCase->testSteps),
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
            $testScripts = $testStep->testScripts;
            $testSetups = $testStep->testSetups;
            $result[] = $this->arrayFilter([
                'path' => $testStep->path,
                'pattern' => $testStep->pattern,
                'method' => $testStep->method,
                'source' => $testStep->source->slug,
                'target' => $testStep->target->slug,
                'mtls' => (bool) $testStep->mtls ?: null,
                'api_spec' => $testStep->apiSpec()->exists()
                    ? $testStep->apiSpec->name
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
                'request' => array_diff_key(
<<<<<<< HEAD
                    $this->arrayFilter($testStep->request->toArray()),
=======
                    array_filter($testStep->request->toArray()),
>>>>>>> fix array filter
                    array_flip(['path'])
                ),
                'response' => array_filter($testStep->response->toArray()),
                'repeat' => $this->mapRepeat($testStep),
            ]);
        }

        return $this->arrayFilter($result);
    }

    /**
     * @param array|null $array
     * @param array $order
     * @return array|null
     */
    protected function withOrder($array, array $order)
    {
        if (!is_array($array)) {
            return $array;
        }
        return array_intersect_key(
            array_merge(array_flip($order), $array),
            $array
        );
    }

    /**
     * @param TestStep $testStep
     * @return array|null
     */
    protected function mapRepeat($testStep)
    {
        $result = [
            'max' => $testStep->repeat_max,
            'count' => $testStep->repeat_count,
            'condition' => $testStep->repeat_condition,
<<<<<<< HEAD
            'response' => $testStep->repeat_response
                ? $this->arrayFilter($testStep->repeat_response->toArray())
                : null,
=======
            'response' => $testStep->repeat_response ?
                array_filter($testStep->repeat_response->toArray()) :
                null,
>>>>>>> fix array filter
            'test_response_scripts' => $this->mapTestScripts(
                $testStep->testScripts,
                TestScript::TYPE_REPEAT_RESPONSE
            ),
        ];
        $result = $this->arrayFilter($result);
        if (2 == count($result)) {
            return null;
        }

        return $result;
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
            : array_filter($array, function ($value) {
                return !is_null($value) && $value !== '';
            });
    }
}
