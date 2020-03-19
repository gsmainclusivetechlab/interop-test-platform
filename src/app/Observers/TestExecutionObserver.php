<?php

namespace App\Observers;

use App\Models\TestExecution;

class TestExecutionObserver
{
    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function pass(TestExecution $testExecution)
    {
        $testExecution->testResult()->increment('passed');
    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function fail(TestExecution $testExecution)
    {
        $testExecution->testResult()->increment('failures');
    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function error(TestExecution $testExecution)
    {
        $testExecution->testResult()->increment('errors');
    }
}
