<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use App\Testing\Tests\ValidateRequestTest;
use PHPUnit\Framework\TestSuite;

class TestRequestSuiteLoader
{
    /**
     * @var TestResult
     */
    protected $testResult;

    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    public function load()
    {
        $suite = new TestRequestSuite();
        $scripts = $this->testResult->testStep->testRequestScripts;

        foreach ($scripts as $script)
        {
            $suite->addTest(new ValidateRequestTest());
        }

        return $suite;
    }
}
