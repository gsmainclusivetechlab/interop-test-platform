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
        $testRun = TestRun::create([
            'session_id' => $testPlan->session_id,
            'test_case_id' => $testPlan->test_case_id,
        ]);

        ProcessTimeoutTestRun::dispatch($testRun)
            ->delay(now()->addMinutes(1));

        try {
            $testStep = $testPlan->testSteps()->firstOrFail();
        } catch (Throwable $e) {
            $testRun->failure($e->getMessage());
            return $e;
        }

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);
        $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $testResult->request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        try {
            $testResult->response = (new Client(['http_errors' => false]))->send($testResult->request);
            return $this->doTest($testResult);
        } catch (Throwable $e) {
            $testResult->failure($e->getMessage());
            return $e;
        }
    }
}
