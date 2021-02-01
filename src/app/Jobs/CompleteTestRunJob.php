<?php

namespace App\Jobs;

use App\Models\TestRun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompleteTestRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TestRun
     */
    protected $testRun;

    /**
     * @param TestRun $testRun
     */
    public function __construct(TestRun $testRun)
    {
        $this->testRun = $testRun;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if (!$this->testRun->completed_at) {
            $latestTestResult = $this->testRun
                ->testResults()
                ->latest()
                ->first();

            if (
                !$latestTestResult ||
                $latestTestResult->created_at->diffInSeconds() >= 30
            ) {
                $this->testRun->complete();
            } else {
                CompleteTestRunJob::dispatch($this->testRun)->delay(
                    now()->addSeconds(30)
                );
            }
        }
    }
}
