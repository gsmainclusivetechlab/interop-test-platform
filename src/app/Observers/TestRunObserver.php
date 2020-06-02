<?php

namespace App\Observers;

use App\Jobs\CompleteTestRunJob;
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
        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));
    }

    /**
     * @param TestRun $testRun
     * @return void
     */
    public function complete(TestRun $testRun)
    {
    }
}
