<?php

namespace App\Observers;

use App\Models\TestRun;

class TestRunObserver
{
    /**
     * @param TestRun $run
     * @return void
     */
    public function pass(TestRun $run)
    {

    }

    /**
     * @param TestRun $run
     * @return void
     */
    public function fail(TestRun $run)
    {

    }

    /**
     * @param TestRun $run
     * @return void
     */
    public function timeout(TestRun $run)
    {

    }
}
