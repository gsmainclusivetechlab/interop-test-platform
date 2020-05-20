<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\BeforeSendingHandler;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
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
     * @param Session $session
     * @param TestCase $testCase
     * @param string $path
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(Session $session, TestCase $testCase, string $path, ServerRequestInterface $request)
    {
        $testStep = $testCase->testSteps()->firstOrFail();
        $testRun = $session->testRuns()->create(['test_case_id' => $testStep->test_case_id]);
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $uri = UriResolver::resolve(
            new Uri($session->getBaseUriOfComponent($testStep->target)),
            new Uri($path)
        );
        $request = $request->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withUri($uri->withQuery((string) request()->getQueryString()));

        return (new PendingRequest())
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->beforeSending(new BeforeSendingHandler($testResult))
            ->transfer($request)
            ->otherwise(new SendingRejectedHandler($testResult))
            ->wait();
    }
}
