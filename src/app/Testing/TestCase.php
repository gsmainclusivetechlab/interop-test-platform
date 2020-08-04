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
        $timer = new Timer();
        $timer->start();

        try {
            $this->test();
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, $timer->stop()->asMilliseconds());
        } catch (Throwable $e) {
            $result->addError($this, $e, $timer->stop()->asMilliseconds());
        } finally {
            $result->endTest($this, $timer->stop()->asMilliseconds());
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
}
