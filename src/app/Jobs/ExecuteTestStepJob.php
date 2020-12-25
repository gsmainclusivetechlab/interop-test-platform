<?php

namespace App\Jobs;

use App\Http\Controllers\Testing\ProcessPendingRequest;
use App\Http\Headers\TraceparentHeader;
use App\Models\Session;
use App\Models\TestRun;
use App\Models\TestStep;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecuteTestStepJob implements ShouldQueue
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
     * @var TestStep
     */
    protected $testStep;

    /**
     * @var TestRun
     */
    protected $testRun;

    /**
     * @param Session $session
     * @param TestStep $testStep
     * @param TestRun $testRun
     */
    public function __construct(
        Session $session,
        TestStep $testStep,
        TestRun $testRun
    ) {
        $this->session = $session;
        $this->testStep = $testStep;
        $this->testRun = $testRun;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $testStep = $this->testStep;
        $testResult = $this->testRun
            ->testResults()
            ->create(['test_step_id' => $testStep->id]);
        $testResult->jobStart = microtime(true);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($this->testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $requestTemplate = $testStep->request;
        $request = $requestTemplate
            ->toPsrRequest()
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withUri(
                UriResolver::resolve(
                    new Uri(
                        ($uri = $this->session->getBaseUriOfComponent(
                            $testStep->target
                        ))
                    ),
                    new Uri($requestTemplate->path(true))
                )->withQuery($requestTemplate->query())
            );

        (new ProcessPendingRequest(
            $request,
            $testResult,
            $this->session,
            empty($uri)
        ))();
    }
}
