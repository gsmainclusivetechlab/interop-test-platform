<?php declare(strict_types=1);

namespace App\Testing;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Timer\Duration;
use Throwable;

abstract class TestCase extends Assert implements Test
{
    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return array
     */
    abstract public function getActual(): array;

    /**
     * @return array
     */
    abstract public function getExpected(): array;

    /**
     * @return void
     */
    abstract protected function test();

    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?? new TestResult();
        $result->startTest($this);
        $startTime = (float) hrtime(true);

        try {
            $this->test();
        } catch (AssertionFailedError $e) {
            $result->addFailure(
                $this,
                $e,
                $this->calculateDurationFromNanoseconds(
                    $startTime
                )->asMilliseconds()
            );
        } catch (Throwable $e) {
            $result->addError(
                $this,
                $e,
                $this->calculateDurationFromNanoseconds(
                    $startTime
                )->asMilliseconds()
            );
        } finally {
            $result->endTest(
                $this,
                $this->calculateDurationFromNanoseconds(
                    $startTime
                )->asMilliseconds()
            );
        }

        return $result;
    }

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * @param float $startTime
     * @return Duration
     */
    protected function calculateDurationFromNanoseconds(float $startTime)
    {
        return Duration::fromNanoseconds((float) hrtime(true) - $startTime);
    }
}
