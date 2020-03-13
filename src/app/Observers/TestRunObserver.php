<?php

namespace App\Observers;

use App\Models\TestRun;

class TestRunObserver
{
    /**
     * @param TestRun $run
     * @return void
     */
    public function passed(TestRun $run)
    {

    }

    /**
     * @param TestRun $run
     * @return void
     */
    public function failure(TestRun $run)
    {

    }
}
