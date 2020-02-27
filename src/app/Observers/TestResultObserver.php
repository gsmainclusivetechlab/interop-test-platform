<?php

namespace App\Observers;

use App\Models\TestResult;

class TestResultObserver
{
    /**
     * @param TestResult $result
     * @return void
     */
    public function pass(TestResult $result)
    {
        if ($result->run->results()->count() >= $result->run->steps()->count()) {
            $result->run->pass();
        }
    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function fail(TestResult $result)
    {
        $result->run->fail();
    }

    /**
     * @param TestResult $result
     * @return void
     */
    public function error(TestResult $result)
    {
        $result->run->fail();
    }
}
