<?php

namespace App\Observers;

use App\Models\TestRun;

class TestRunObserver
{
    /**
     * @param TestRun $testRun
     * @return void
     */
    public function created(TestRun $testRun)
    {
        $testRun->increment('total', $testRun->testSteps()->count());
    }

    /**
     * @param TestRun $testRun
     * @return void
     */
    public function complete(TestRun $testRun)
    {

    }
}
