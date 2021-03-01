<?php

namespace App\Jobs;

use App\Http\Controllers\Testing\ProcessPendingRequest;
use App\Http\Headers\TraceparentHeader;
use App\Models\{Session, TestRun, TestStep};
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExecuteTestRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * @var TestRun
     */
    protected $testRun;

    /**
     * ExecuteTestRunJob constructor.
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
        /** @var Session $session */
        $session = $this->testRun->session;
        /** @var TestStep $testStep */
        $testStep = $this->testRun->testCase->testSteps()->firstOrFail();
        $testRun = $this->testRun;
        $testResult = $testRun->createTestResult($testStep);

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $requestTemplate = $testStep->request->withSubstitutions(
            $testRun->testResults,
            $session,
            $testStep
        );
        $request = $requestTemplate
            ->toPsrRequest()
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withUri(
                UriResolver::resolve(
                    new Uri(
                        ($uri = $session->getBaseUriOfComponent(
                            $testStep->target,
                            null,
                            true
                        ))
                    ),
                    new Uri($requestTemplate->urlForResolver())
                )->withQuery($requestTemplate->query())
            );

        (new ProcessPendingRequest(
            $request,
            $testResult,
            $session,
            empty($uri)
        ))();
    }
}
