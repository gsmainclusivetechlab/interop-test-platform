<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Timer\Timer;
use Throwable;

abstract class TestCase extends Assert implements Test
{
    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?: $this->createResult();
        Timer::start();
        $result->startTest($this);

        try {
            $this->test();
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, Timer::stop());
        } catch (Throwable $e) {
            $result->addError($this, $e, Timer::stop());
        }

        $result->endTest($this, Timer::stop());
        return $result;
    }

    /**
     * @return void
     */
    abstract public function test();

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * @return TestResult
     */
    protected function createResult()
    {
        return new TestResult;
    }
}
