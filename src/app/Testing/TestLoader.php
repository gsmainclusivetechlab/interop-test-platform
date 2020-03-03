<?php declare(strict_types=1);

namespace App\Testing;

use App\Models\TestRun;
use App\Models\TestStep;
use PHPUnit\Framework\TestSuite;

class TestLoader
{
    /**
     * @param TestRun $run
     * @param TestStep $step
     * @return TestSuite
     */
    public function load(TestRun $run, TestStep $step): TestSuite
    {
        $suite = new TestSuite();
        return $suite;
    }
}
