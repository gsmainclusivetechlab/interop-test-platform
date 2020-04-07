<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Uri;
use SebastianBergmann\Timer\Timer;

class TestController extends Controller
{
    use HasPsrRequest;

    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class, ValidateTraceContext::class]);
    }

    /**
     * @param string $uri
     * @return mixed
     */
    public function __invoke(string $uri)
    {
        $request = $this->getRequest();
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()->offset($testRun->testResults()->count())->firstOrFail();
        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
        ]);

        $uri = (new Uri($uri));
        $request = $request->withUri($uri);

        Timer::start();
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());
        $stack->push(new MapRequestHandler($testResult));
        $stack->push(new MapResponseHandler($testResult));
        $promise = (new Client(['handler' => $stack, 'http_errors' => false]))->sendAsync($request);

        return $promise->then(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult))->wait();
    }
}
