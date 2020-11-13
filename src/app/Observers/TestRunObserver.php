<?php

namespace App\Observers;

use App\Jobs\CompleteTestRunJob;
use App\Models\Session;
use App\Models\TestRun;

class TestRunObserver
{
    /**
     * @param TestRun $testRun
     * @return void
     */
    public function created(TestRun $testRun)
    {
        $session = $testRun->session;

        if ($session->isCompliance() && $session->isStatusReady()) {
            $session->updateStatus(Session::STATUS_IN_EXECUTION);
        }

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
