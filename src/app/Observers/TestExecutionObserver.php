<?php

namespace App\Observers;

use App\Models\TestExecution;

class TestExecutionObserver
{
    /**
     * @param TestExecution $execution
     * @return void
     */
    public function passed(TestExecution $execution)
    {

    }

    /**
     * @param TestExecution $execution
     * @return void
     */
    public function failure(TestExecution $execution)
    {

    }
}
