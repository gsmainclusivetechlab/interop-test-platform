<?php

namespace App\Observers;

use App\Models\TestExecution;

class TestExecutionObserver
{
    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function successful(TestExecution $testExecution)
    {
        $testExecution->testResult()->increment('total');
        $testExecution->testResult()->increment('passed');
    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function unsuccessful(TestExecution $testExecution)
    {
        $testExecution->testResult()->increment('total');
        $testExecution->testResult()->increment('failures');
    }
}
