<?php declare(strict_types=1);

namespace App\Testing;

use App\Enums\TestGroupEnum;
use App\Models\TestResult;
use App\Testing\Tests\ValidateRequestSchemaTest;
use App\Testing\Tests\ValidateRequestScriptTest;
use App\Testing\Tests\ValidateResponseSchemaTest;
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

        if ($apiService = $testResult->testStep->targetApiService) {
            $testSuite->addTest(new ValidateRequestSchemaTest($testResult, $apiService));
        }

        if ($testRequestScripts = $testResult->testStep->testScripts()->where('group', TestGroupEnum::REQUEST)->get()) {
            foreach ($testRequestScripts as $testRequestScript) {
                $testSuite->addTest(new ValidateRequestScriptTest($testResult, $testRequestScript));
            }
        }

        if ($apiService = $testResult->testStep->targetApiService) {
            $testSuite->addTest(new ValidateResponseSchemaTest($testResult, $apiService));
        }

        if ($testResponseScripts = $testResult->testStep->testScripts()->where('group', TestGroupEnum::RESPONSE)->get()) {
            foreach ($testResponseScripts as $testResponseScript) {
                $testSuite->addTest(new ValidateResponseScriptTest($testResult, $testResponseScript));
            }
        }

        return $testSuite;
    }
}
