<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $result
     * @return void
     */
    public function passed(TestResult $result)
    {

    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function failure(TestResult $result)
    {

    }
}
