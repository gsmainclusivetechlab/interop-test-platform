<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\CompleteTestRunJob;
use App\Models\Session;
use App\Models\TestCase;
use App\Testing\Middlewares\RequestMiddleware;
use App\Testing\Middlewares\ResponseMiddleware;
use App\Testing\TestRequest;
use App\Testing\TestResponse;
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
     * @param Session $session
     * @param TestCase $testCase
     * @param string $path
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, Session $session, TestCase $testCase, string $path)
    {
        $testRun = $session->testRuns()->create([
            'test_case_id' => $testCase->id,
        ]);
        $testStep = $testCase->testSteps()->firstOrFail();

        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

        $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

//        foreach ($testStep->testRequestSetups()->get() as $testRequestSetup) {
//            $stack->push(new RequestMiddleware($testRequestSetup));
//        }
//
//        foreach ($testStep->testResponseSetups()->get() as $testResponseSetup) {
//            $stack->push(new ResponseMiddleware($testResponseSetup));
//        }

        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
            'request' => new TestRequest($request),
        ]);

        try {
            $response = (new Client(['handler' => $stack, 'http_errors' => false]))->send($request);
            $testResult->response = new TestResponse($response);
            $this->doTest($testResult);
            $testResult->complete();

            return $response;
        } catch (RequestException $e) {
            return $e;
        }
    }
}
