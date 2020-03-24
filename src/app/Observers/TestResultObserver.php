<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $testResult
     * @return void
     */
    public function creating(TestResult $testResult)
    {
        $testResult->total = $testResult->testScripts()->count();
    }

    /**
     * @param TestResult $testResult
     * @return void
     */
    public function complete(TestResult $testResult)
    {
        $testResult->testRun->increment($testResult->successful ? 'passed' : 'failures');
        $testResult->testRun->increment('duration', floor((microtime(true) - LARAVEL_START) * 1000));

        if ($testResult->testStep->isLastPosition()) {
            $testResult->testRun->complete();
        }
    }
}
