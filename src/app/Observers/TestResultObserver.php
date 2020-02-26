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
        dd(1);
    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function failed(TestResult $result)
    {
        $result->run->complete();
    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function error(TestResult $result)
    {
        $result->run->complete();
    }
}
