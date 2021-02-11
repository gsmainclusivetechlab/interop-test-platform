<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Tests\RepeatResponseScriptValidationTest;
use App\Testing\Tests\RequestScriptValidationTest;
use App\Testing\Tests\ResponseScriptValidationTest;
use PHPUnit\Framework\TestSuite;

class TestScriptLoader
{
    /**
     * @param TestResult $testResult
     * @param bool $isRepeat
     * @return TestSuite
     */
    public function load(TestResult $testResult, bool $isRepeat)
    {
        $testSuite = new TestSuite();

        if (
            $testRequestScripts = $testResult->testStep
                ->testScripts()
                ->ofType(TestScript::TYPE_REQUEST)
                ->get()
        ) {
            foreach ($testRequestScripts as $testRequestScript) {
                $testSuite->addTest(
                    new RequestScriptValidationTest(
                        $testResult,
                        $testRequestScript
                    )
                );
            }
        }

        if (
            $testResponseScripts = $testResult->testStep
                ->testScripts()
                ->ofType($isRepeat
                    ? TestScript::TYPE_REPEAT_RESPONSE
                    : TestScript::TYPE_RESPONSE
                )
                ->get()
        ) {
            foreach ($testResponseScripts as $testResponseScript) {
                $testSuite->addTest($isRepeat ?
                    new RepeatResponseScriptValidationTest(
                        $testResult,
                        $testResponseScript
                    ) :
                    new ResponseScriptValidationTest(
                        $testResult,
                        $testResponseScript
                    )
                );
            }
        }

        return $testSuite;
    }
}
