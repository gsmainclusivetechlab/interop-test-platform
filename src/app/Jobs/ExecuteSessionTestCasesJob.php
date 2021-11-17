<?php

namespace App\Jobs;

use App\Models\{Session, TestCase, TestRun};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecuteSessionTestCasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var array
     */
    protected $testCaseIds;

    /**
     * @var TestRun
     */
    protected $currentTestRun;

    /**
     * ExecuteSessionTestCasesJob constructor.
     * @param Session $session
     * @param array $testCaseIds
     * @param TestRun|null $currentTestRun
     */
    public function __construct(Session $session, array $testCaseIds = [], TestRun $currentTestRun = null)
    {
        $this->session = $session;
        $this->testCaseIds = $testCaseIds;
        $this->currentTestRun = $currentTestRun;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if ($currentTestRun = $this->currentTestRun) {
            if ($currentTestRun->isCompleted()) {
                $this->nextTestRun();
            } else {
                ExecuteSessionTestCasesJob::dispatch(
                    $this->session,
                    $this->testCaseIds,
                    $this->currentTestRun
                )->delay(
                    now()->addSeconds(5)
                );
            }
        } else {
            $this->nextTestRun();
        }

    }

    /**
     * @return void
     */
    protected function nextTestRun()
    {
        $testCaseIds = $this->testCaseIds;
        $testCaseId = array_shift($testCaseIds);
        $testRun = null;
        $testCase = TestCase::find($testCaseId);

        if ($testCase) {
            /** @var Session $session */
            $session = $this->session;
            /** @var TestRun $testRun */
            $testRun = $session
                ->testRuns()
                ->create(['test_case_id' => $testCase->id]);
            ExecuteTestRunJob::dispatch($testRun);
        }

        if ($testCaseIds) {
            ExecuteSessionTestCasesJob::dispatch(
                $this->session,
                $testCaseIds,
                $testRun
            )->delay(
                now()->addSeconds(5)
            );
        }
    }
}
