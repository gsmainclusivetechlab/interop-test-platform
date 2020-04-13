<?php

namespace App\Jobs;

use App\Http\Controllers\Testing\RunController;
use App\Models\TestDatum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunTestDatumJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TestDatum
     */
    protected $testDatum;

    /**
     * @param TestDatum $testDatum
     */
    public function __construct(TestDatum $testDatum)
    {
        $this->testDatum = $testDatum;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $run = app(RunController::class);
        $run->setRequest($this->testDatum->request->toPsrRequest());
        $run($this->testDatum->session, $this->testDatum->testCase, $this->testDatum->request->url());
    }
}
