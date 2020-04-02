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
        $testRun = $session->testRuns()->create([
            'test_case_id' => $testCase->id,
        ]);
        $testStep = $testCase->testSteps()->firstOrFail();
        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
        ]);

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
        $stack->push(new MapRequestHandler($testResult));
        $stack->push(new MapResponseHandler($testResult));
        $promise = (new Client(['handler' => $stack, 'http_errors' => false]))->sendAsync($request);

        return $promise->then(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult))->wait();
    }
}
