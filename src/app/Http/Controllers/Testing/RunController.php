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
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Session $session
     * @param TestCase $testCase
     * @param string $path
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, Session $session, TestCase $testCase, string $path)
    {
        $testRun = tap($session->testRuns()->make(), function ($testRun) use ($testCase) {
            $testRun->testCase()
                ->associate($testCase)
                ->save();
        });
        $testRun->increment('total', $testCase->testSteps()->count());
        $testStep = $testRun->testCase->testSteps()->firstOrFail();
        $testResult = tap($testRun->testResults()->make(), function ($testResult) use ($testStep) {
            $testResult->testStep()
                ->associate($testStep)
                ->save();
        });

        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

        $baseUrl = $session->suts()->whereKey($testStep->target->id)->value('base_url')
            ??
            $testStep->target->apiService->base_url;
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri(UriResolver::resolve(new Uri($baseUrl), new Uri($path)))
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }
}
