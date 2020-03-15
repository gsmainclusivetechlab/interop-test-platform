<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\ProcessTimeoutTestRun;
use App\Models\TestPlan;
use App\Models\TestRun;
use App\Testing\Middlewares\RequestMiddleware;
use App\Testing\Middlewares\ResponseMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
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

        ProcessTimeoutTestRun::dispatch($testRun)->delay(now()->addMinutes(1));

        try {
            $testStep = $testPlan->testSteps()->firstOrFail();
            $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
            $traceparent = (new TraceparentHeader())
                ->withTraceId($testRun->trace_id)
                ->withVersion(TraceparentHeader::DEFAULT_VERSION);
            $request = $request->withUri($uri)
                ->withMethod($request->getMethod())
                ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

            $stack = new HandlerStack();
            $stack->setHandler(new CurlHandler());
            $stack->push(new RequestMiddleware($testStep->testRequestSetups()->first()));
//            $stack->push(new ResponseMiddleware($testStep->testResponseSetups()->first()));

            $response = (new Client(['handler' => $stack, 'http_errors' => false]))->send($request);
            $testResult = $testRun->testResults()->create([
                'test_step_id' => $testStep->id,
                'request' => $request,
                'response' => $response,
            ]);

            return $this->doTest($testResult);
        } catch (Throwable $e) {
            $testRun->failure($e->getMessage());
            return $e;
        }
    }
}
