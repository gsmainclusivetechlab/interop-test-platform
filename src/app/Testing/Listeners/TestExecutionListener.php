<?php declare(strict_types=1);

namespace App\Testing\Listeners;

use App\Models\TestResult;
use App\Testing\TestCase;
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
    protected $result;

    /**
     * @param TestResult $result
     */
    public function __construct(TestResult $result)
    {
        $this->result = $result;
    }

    public function startTest(Test $test): void
    {
        $this->lastTestWasNotSuccessful = false;
    }

    public function addError(Test $test, Throwable $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->result->testExecutions()->make()->error($test->getScript(), $e->getMessage());
            $this->result->increment('errors');
        }

        $this->lastTestWasNotSuccessful = true;
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        if ($test instanceof TestCase) {
            $this->result->testExecutions()->make()->fail($test->getScript(), $e->getMessage());
            $this->result->increment('failures');
        }

        $this->lastTestWasNotSuccessful = true;
    }

    public function endTest(Test $test, float $time): void
    {
        if (!$this->lastTestWasNotSuccessful) {
            if ($test instanceof TestCase) {
                $this->result->testExecutions()->make()->pass($test->getScript());
                $this->result->increment('passed');
            }
        }
    }
}
