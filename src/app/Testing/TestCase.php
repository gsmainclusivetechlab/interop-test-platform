<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestScript;
use App\Testing\Concerns\InteractsWithValidation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\Timer\Timer;
use Throwable;

abstract class TestCase extends Assert implements Test
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
     * @return TestScript
     */
    abstract public function getScript(): TestScript;

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
