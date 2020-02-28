<?php

namespace App\Jobs;

use App\Models\TestRun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTimeoutTestRun implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TestRun
     */
    protected $run;

    /**
     * @param TestRun $run
     */
    public function __construct(TestRun $run)
    {
        $this->run = $run;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if (!$this->run->completed_at) {
            $this->run->timeout();
        }
    }
}
