<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestRun;
use App\Models\TestStep;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener as BaseTestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class TestListener implements BaseTestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @var TestRun
     */
    protected $run;

    /**
     * @var TestStep
     */
    protected $step;

    /**
     * @param TestRun $run
     * @param TestStep $step
     */
    public function __construct(TestRun $run, TestStep $step)
    {
        $this->run = $run;
        $this->step = $step;
    }

    /**
     * @param Test $test
     * @param AssertionFailedError $e
     * @param float $time
     */
    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        dd($test);
    }
}
