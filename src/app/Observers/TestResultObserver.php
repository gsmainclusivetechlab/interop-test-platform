<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $testResult
     * @return void
     */
    public function passed(TestResult $testResult)
    {

    }

    /**
     * @param TestResult $testResult
     * @return void
     */
    public function failure(TestResult $testResult)
    {

    }
}
