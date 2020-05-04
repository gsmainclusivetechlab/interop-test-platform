<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use Throwable;

class TestExecutionListener implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @var bool
     */
    protected $lastTestWasNotSuccessful;

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
     * @param Test $test
     */
    public function startTest(Test $test): void
    {
        $this->lastTestWasNotSuccessful = false;
    }

    /**
     * @param Test $test
     * @param Throwable $e
     * @param float $time
     */
    public function addError(Test $test, Throwable $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->testResult->testExecutions()->make([
                'name' => $test->getName(),
                'actual' => $test->getActual(),
                'expected' => $test->getExpected(),
            ])->fail($e->getMessage());
        }

        $this->lastTestWasNotSuccessful = true;
    }

    /**
     * @param Test $test
     * @param AssertionFailedError $e
     * @param float $time
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->testResult->testExecutions()->make([
                'name' => $test->getName(),
                'actual' => $test->getActual(),
                'expected' => $test->getExpected(),
            ])->fail($e->getMessage());
        }

        $this->lastTestWasNotSuccessful = true;
    }

    /**
     * @param Test $test
     * @param float $time
     */
    public function endTest(Test $test, float $time): void
    {
        if (!$this->lastTestWasNotSuccessful) {
            if ($test instanceof TestCase) {
                $this->testResult->testExecutions()->make([
                    'name' => $test->getName(),
                    'actual' => $test->getActual(),
                    'expected' => $test->getExpected(),
                ])->pass();
            }
        }
    }
}
