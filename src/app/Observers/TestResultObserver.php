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
        $testResult->testRun()->increment($testResult->successful ? 'passed' : 'failures');
        $testResult->testRun()->increment('duration', floor((microtime(true) - LARAVEL_START) * 1000));
    }
}
