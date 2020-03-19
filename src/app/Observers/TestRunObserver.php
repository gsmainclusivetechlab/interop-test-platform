<?php

namespace App\Observers;

use App\Models\TestRun;

class TestRunObserver
{
    /**
     * @param TestRun $testRun
     * @return void
     */
    public function passed(TestRun $testRun)
    {

    }

    /**
     * @param TestRun $testRun
     * @return void
     */
    public function failure(TestRun $testRun)
    {

    }
}
