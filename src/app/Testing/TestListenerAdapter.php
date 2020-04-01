<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestResult;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use Throwable;

class TestListenerAdapter implements TestListener
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

    public function startTest(Test $test): void
    {
        $this->lastTestWasNotSuccessful = false;
    }

    public function addError(Test $test, Throwable $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->testResult->testExecutions()->create([
                'name' => $test->getName(),
                'message' => $e->getMessage(),
                'successful' => false,
            ]);
        }

        $this->lastTestWasNotSuccessful = true;
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->testResult->testExecutions()->create([
                'name' => $test->getName(),
                'message' => $e->getMessage(),
                'successful' => false,
            ]);
        }

        $this->lastTestWasNotSuccessful = true;
    }

    public function endTest(Test $test, float $time): void
    {
        if (!$this->lastTestWasNotSuccessful) {
            if ($test instanceof TestCase) {
                $this->testResult->testExecutions()->create([
                    'name' => $test->getName(),
                    'successful' => true,
                ]);
            }
        }
    }
}
