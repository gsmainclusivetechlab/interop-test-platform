<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\CompleteTestRunJob;
use App\Models\Session;
use App\Models\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use SebastianBergmann\Timer\Timer;

class RunController extends Controller
{
    use HasPsrRequest;

    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class]);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param string $path
     * @return mixed
     */
    public function __invoke(Session $session, TestCase $testCase, string $path)
    {
        $testStep = $testCase->testSteps()->firstOrFail();
        $testRun = $session->testRuns()->create([
            'test_case_id' => $testCase->id,
        ]);
        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
        ]);

        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

        $baseUrl = $session->suts()->whereKey($testStep->target->id)->value('base_url') ?? $testStep->target->apiService->base_url;
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $this->getRequest()
            ->withUri(UriResolver::resolve(new Uri($baseUrl), new Uri($path)))
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        Timer::start();

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }
}
