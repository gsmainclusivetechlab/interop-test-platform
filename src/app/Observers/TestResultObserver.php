<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $testResult
     * @return void
     */
    public function pass(TestResult $testResult)
    {
        $testResult->testRun()->increment('passed');
        $testResult->testRun()->increment('duration', floor((microtime(true) - LARAVEL_START) * 1000));
    }

    /**
     * @param TestResult $testResult
     * @return void
     */
    public function fail(TestResult $testResult)
    {
        $testResult->testRun()->increment('failures');
        $testResult->testRun()->increment('duration', floor((microtime(true) - LARAVEL_START) * 1000));
    }
}
