<?php declare(strict_types=1);

namespace App\Testing;

use App\Testing\Concerns\InteractsWithValidation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\SelfDescribing;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Timer\Timer;
use Throwable;

abstract class TestCase extends Assert implements Test, SelfDescribing
{
    use InteractsWithValidation;

    /**
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * @return void
     */
    abstract protected function doTest();

    /**
     * @param TestResult|null $result
     * @return TestResult
     */
    public function run(TestResult $result = null): TestResult
    {
        $result = $result ?? new TestResult();
        $result->startTest($this);
        Timer::start();

        try {
            $this->doTest();
        } catch (AssertionFailedError $e) {
            $result->addFailure($this, $e, Timer::stop());
        } catch (Throwable $e) {
            $result->addError($this, $e, Timer::stop());
        } finally {
            $result->endTest($this, Timer::stop());
        }

        return $result;
    }
}
