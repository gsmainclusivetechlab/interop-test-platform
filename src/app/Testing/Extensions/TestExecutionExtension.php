<?php declare(strict_types=1);

namespace App\Testing\Extensions;

use App\Models\TestResult;
use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;

class TestExecutionExtension implements AfterSuccessfulTestHook, AfterTestFailureHook, AfterTestErrorHook
{
    /**
     * @var TestResult
     */
    protected $result;

    /**
     * @param TestResult $result
     */
    public function __construct(TestResult $result)
    {
        $this->result = $result;
    }

    /**
     * @param string $test
     * @param float $time
     */
    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        $this->result->testExecutions()->make()->pass($test);
    }

    /**
     * @param string $test
     * @param string $message
     * @param float $time
     */
    public function executeAfterTestFailure(string $test, string $message, float $time): void
    {
        $this->result->testExecutions()->make()->fail($test, $message);
    }

    /**
     * @param string $test
     * @param string $message
     * @param float $time
     */
    public function executeAfterTestError(string $test, string $message, float $time): void
    {
        $this->result->testExecutions()->make()->error($test, $message);
    }
}
