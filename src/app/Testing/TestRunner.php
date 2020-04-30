<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Tests\ValidateApiScheme;
use App\Testing\Tests\ValidateRequestScriptTest;
use App\Testing\Tests\ValidateResponseScriptTest;
use League\Pipeline\Pipeline;

class TestRunner
{
    /**
     * @param TestResult $testResult
     * @return TestResult
     */
    public function run(TestResult $testResult)
    {
        $pipeline = new Pipeline(new TestProcessor());

        if ($apiScheme = $testResult->testStep->apiScheme) {
            $pipeline = $pipeline->pipe(new ValidateApiScheme($apiScheme));
        }

        if ($testRequestScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_REQUEST)->get()) {
            foreach ($testRequestScripts as $testRequestScript) {
                $pipeline = $pipeline->pipe(new ValidateRequestScriptTest($testRequestScript));
            }
        }

        if ($testResponseScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_RESPONSE)->get()) {
            foreach ($testResponseScripts as $testResponseScript) {
                $pipeline = $pipeline->pipe(new ValidateResponseScriptTest($testResponseScript));
            }
        }

        return $pipeline->process($testResult)->wasSuccessful() ? $testResult->pass() : $testResult->fail();
    }
}
