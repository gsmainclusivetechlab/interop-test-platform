<?php

namespace App\Utils;

use App\Http\Client\{Request, Response};
use App\Models\TestResult;
use Storage;
use Throwable;
use V8Js;

class SimulatorPlugin
{
    protected string $type;

    /** @var Request|Response */
    protected $requestOrResponse;

    protected TestResult $testResult;

    /**
     * SimulatorPlugin constructor.
     * @param Request|Response $requestOrResponse
     * @param TestResult $testResult
     */
    public function __construct($requestOrResponse, TestResult $testResult)
    {
        $this->type =
            $requestOrResponse instanceof Request ? 'request' : 'response';

        $this->requestOrResponse = $requestOrResponse;
        $this->testResult = $testResult;
    }

    /**
     * @return mixed
     * @throws Throwable
     */
    public function process()
    {
        throw_if(
            !$this->testResult->session->simulatorPlugin,
            'Session must have simulator plugin'
        );

        $v8 = new V8Js();
        $v8->setModuleLoader(fn($path) => Storage::get($path));

        $result = $v8->executeString($this->getJsString());

        return json_decode($result, true);
    }

    protected function getJsString(): string
    {
        $env = json_encode($this->session->environments ?? []);

        return <<<EOT
const main = require('{$this->testResult->session->simulatorPlugin->file_path}');
const step = JSON.parse('{$this->getStepJson()}');
const env = JSON.parse('$env');

result = JSON.stringify(main(step, '$this->type', '{$this->testResult->testStep->testCase->slug}', env));
result;
EOT;
    }

    public function getStepJson(): string
    {
        $testStep = $this->testResult->testStep;
        $isRequest = $this->requestOrResponse instanceof Request;

        return json_encode([
            'source' => $testStep->source->slug,
            'target' => $testStep->target->slug,
            'request' => ($isRequest
                ? $this->requestOrResponse
                : $this->testResult->request
            )->toArray(),
            'response' => $isRequest
                ? null
                : $this->requestOrResponse->toArray(),
            'iteration_count' => $this->testResult->iteration,
        ]);
    }
}
