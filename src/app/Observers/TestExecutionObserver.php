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

    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function fail(TestExecution $testExecution)
    {

    }
}
