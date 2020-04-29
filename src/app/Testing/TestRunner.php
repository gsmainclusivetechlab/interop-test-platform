<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Tests\ValidateApiScheme;
use App\Testing\Tests\ValidateRequestScriptTest;
use App\Testing\Tests\ValidateResponseScriptTest;
use Illuminate\Pipeline\Pipeline;

class TestRunner
{
    /**
     * @param TestResult $testResult
     * @return TestResult
     */
    public function run(TestResult $testResult)
    {
        $pipes = [];

        if ($apiScheme = $testResult->testStep->apiScheme) {
            $pipes[] = new ValidateApiScheme($apiScheme);
        }

        if ($testRequestScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_REQUEST)->get()) {
            foreach ($testRequestScripts as $testRequestScript) {
                $pipes[] = new ValidateRequestScriptTest($testRequestScript);
            }
        }

        if ($testResponseScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_RESPONSE)->get()) {
            foreach ($testResponseScripts as $testResponseScript) {
                $pipes[] = new ValidateResponseScriptTest($testResponseScript);
            }
        }

        return (new Pipeline())
            ->send($testResult)
            ->through($pipes)
            ->then(function ($testResult) {
                return $testResult->complete();
            });
    }
}
