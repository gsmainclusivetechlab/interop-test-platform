<?php

namespace App\Observers;

use App\Models\TestExecution;

class TestExecutionObserver
{
    /**
     * @param TestExecution $execution
     * @return void
     */
    public function pass(TestExecution $execution)
    {

    }

    /**
     * @param TestExecution $execution
     * @return void
     */
    public function fail(TestExecution $execution)
    {

    }

    /**
     * @param TestExecution $execution
     * @return void
     */
    public function error(TestExecution $execution)
    {

    }
}
