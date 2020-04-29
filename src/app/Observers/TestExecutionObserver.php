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

    }

    /**
     * @param TestExecution $testExecution
     * @return void
     */
    public function unsuccessful(TestExecution $testExecution)
    {

    }
}
