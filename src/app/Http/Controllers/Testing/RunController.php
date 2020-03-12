<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\ProcessTimeoutTestRun;
use App\Models\TestPlan;
use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

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
     * @param TestPlan $plan
     * @param string $path
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, TestPlan $testPlan, string $path)
    {
        $testStep = $testPlan->testSteps()->firstOrFail();

        $testRun = TestRun::create([
            'session_id' => $testPlan->session_id,
            'test_case_id' => $testPlan->test_case_id,
        ]);
        $testResult = $testRun->testResults()->make([
            'test_step_id' => $testStep->id,
            'request' => [],
            'response' => [],
            'total' => 0,
            'passed' => 0,
            'errors' => 0,
            'failures' => 0,
            'time' => 0,
        ]);

//        ProcessTimeoutTestRun::dispatch($run)
//            ->delay(now()->addMinutes(1));

        $uri = (new Uri($testStep->target->apiService->server))
            ->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        $response = (new Client(['http_errors' => false]))->send($request);
        $testResult->save();

        return $response;
        dd($response);

        return $this->doTest($request, $testRun, $testStep);
    }
}
