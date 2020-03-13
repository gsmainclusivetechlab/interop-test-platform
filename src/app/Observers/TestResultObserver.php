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
        if ($result->testRun->testResults()->count() >= $result->testRun->testSteps()->count()) {
            $result->testRun->passed();
        }
    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function failure(TestResult $result)
    {
        $result->testRun->failure();
    }
}
