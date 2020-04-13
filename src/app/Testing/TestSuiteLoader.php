<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Models\TestScript;
use App\Testing\Tests\ValidateOpenApiSchemaTest;
use App\Testing\Tests\ValidateRequestScriptTest;
use App\Testing\Tests\ValidateResponseScriptTest;
use PHPUnit\Framework\TestSuite;

class TestSuiteLoader
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @param TestResult $testResult
     */
    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @return TestSuite
     */
    public function load()
    {
        $testSuite = new TestSuite();
        $testResult = $this->testResult;
        $connection = $testResult->testStep->source->paths()
            ->wherePivot('target_id', $testResult->testStep->target->id)
            ->first();

        if ($apiScheme = $connection->pivot->apiScheme) {
            $testSuite->addTest(new ValidateOpenApiSchemaTest($testResult, $apiScheme));
        }

        if ($testRequestScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_REQUEST)->get()) {
            foreach ($testRequestScripts as $testRequestScript) {
                $testSuite->addTest(new ValidateRequestScriptTest($testResult, $testRequestScript));
            }
        }

        if ($testResponseScripts = $testResult->testStep->testScripts()->ofType(TestScript::TYPE_RESPONSE)->get()) {
            foreach ($testResponseScripts as $testResponseScript) {
                $testSuite->addTest(new ValidateResponseScriptTest($testResult, $testResponseScript));
            }
        }

        return $testSuite;
    }
}
