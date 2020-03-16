<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\CompleteTestRun;
use App\Models\TestPlan;
use App\Models\TestRun;
use App\Testing\Middlewares\RequestMiddleware;
use App\Testing\Middlewares\ResponseMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
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
        $testStep = $testPlan->testSteps()->firstOrFail();

        CompleteTestRun::dispatch($testRun)->delay(now()->addSeconds(5));

        $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

        foreach ($testStep->testRequestSetups()->get() as $testRequestSetup) {
            $stack->push(new RequestMiddleware($testRequestSetup));
        }

        foreach ($testStep->testResponseSetups()->get() as $testResponseSetup) {
            $stack->push(new ResponseMiddleware($testResponseSetup));
        }

        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
            'request' => $request,
        ]);

        try {
            $response = (new Client(['handler' => $stack, 'http_errors' => false]))->send($request);
            $testResult->update([
                'response' => $response,
            ]);

            return $this->doTest($testResult);
        } catch (RequestException $e) {
            $testResult->failure($e->getMessage());
            return $e;
        }
    }
}
