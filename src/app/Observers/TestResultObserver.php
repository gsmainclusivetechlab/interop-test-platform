<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $testResult
     * @return void
     */
    public function complete(TestResult $testResult)
    {
        if ($testResult->isLast()) {
            $testResult->testRun->complete();
        }
    }
}
