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
use V8JsScriptException;

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
        $testResult = $this->testRun->createTestResult($testStep);
        $testResult->jobStart = microtime(true);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($this->testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);

        try {
            $requestTemplate = $testStep->request
                ->withSubstitutions(
                    $this->testRun->testResults,
                    $this->session,
                    $testStep
                )
                ->withPlugin($testResult);
        } catch (V8JsScriptException $e) {
            $testResult->fail($e->getMessage());

            return;
        }

        $request = $requestTemplate
            ->toPsrRequest()
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withUri(
                UriResolver::resolve(
                    new Uri(
                        ($uri = $this->session->getBaseUriOfComponent(
                            $testStep->target,
                            '',
                            true
                        ))
                    ),
                    new Uri($requestTemplate->urlForResolver())
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
