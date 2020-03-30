<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\CompleteTestRunJob;
use App\Models\Session;
use App\Models\TestCase;
use GuzzleHttp\Psr7\Uri;
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


    public function __invoke(ServerRequestInterface $request, Session $session, TestCase $testCase, string $path)
    {
        $testRun = $session->testRuns()->create(['test_case_id' => $testCase->id]);
        $testStep = $testCase->testSteps()->firstOrFail();
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

//        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

        $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        $response = $this->doTest($request, $testResult);

        dd($response);
    }
}
