<?php

namespace App\Jobs;

use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Controllers\Testing\PendingRequest;
use App\Http\Headers\TraceparentHeader;
use App\Models\Session;
use App\Models\TestCase;
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
     * @var Session
     */
    protected $session;

    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @param Session $session
     * @param TestCase $testCase
     */
    public function __construct(Session $session, TestCase $testCase)
    {
        $this->session = $session;
        $this->testCase = $testCase;
    }

    /**
     * @return void
     */
    public function handle()
    {
        $testStep = $this->testCase->testSteps()->firstOrFail();
        $testRun = $this->session
            ->testRuns()
            ->create(['test_case_id' => $testStep->test_case_id]);
        $testResult = $testRun
            ->testResults()
            ->create(['test_step_id' => $testStep->id]);

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $testStep->request
            ->toPsrRequest()
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withUri(
                UriResolver::resolve(
                    new Uri(
                        $this->session->getBaseUriOfComponent($testStep->target)
                    ),
                    new Uri($testStep->request->path())
                )
            );

        (new PendingRequest())
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->transfer($request)
            ->then(new SendingFulfilledHandler($testResult))
            ->otherwise(new SendingRejectedHandler($testResult))
            ->wait();
    }
}
