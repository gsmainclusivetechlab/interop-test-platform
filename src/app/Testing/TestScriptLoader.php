<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Tests\RequestScriptValidationTest;
use App\Testing\Tests\ResponseScriptValidationTest;
use PHPUnit\Framework\TestSuite;

class TestScriptLoader
{
    /**
     * @param TestResult $testResult
     * @return TestSuite
     */
    public function load(TestResult $testResult)
    {
        $testSuite = new TestSuite();

        if ($testRequestScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_REQUEST)->get()) {
            foreach ($testRequestScripts as $testRequestScript) {
                $testSuite->addTest(new RequestScriptValidationTest($testResult->request, $testRequestScript));
            }
        }

        if ($testResponseScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_RESPONSE)->get()) {
            foreach ($testResponseScripts as $testResponseScript) {
                $testSuite->addTest(new ResponseScriptValidationTest($testResult->response, $testResponseScript));
            }
        }

        return $testSuite;
    }
}
