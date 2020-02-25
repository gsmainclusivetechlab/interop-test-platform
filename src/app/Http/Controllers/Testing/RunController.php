<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\TestPlan;
use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['api', SetJsonHeaders::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param TestPlan $plan
     * @param string|null $path
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, TestPlan $plan, string $path = null)
    {
        $step = $plan->steps()
            ->whereRaw('? like path', $path)
            ->where('method', $request->getMethod())
            ->firstOrFail();

        $run = TestRun::create([
            'case_id' => $plan->case_id,
            'session_id' => $plan->session_id,
        ]);

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($run->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);
        $response = (new Client(['http_errors' => false]))->send($request);

        return $response;

//        $suite = new TestSuite();
//        $suite->addTest(new ValidateRequestTest($request, []));
//        $suite->addTest(new ValidateResponseTest($response, []));
//        $result = new TestResult();
//        $result = $suite->run($result);
//
//        dd($result->wasSuccessful());

//        $runResult = $run->results()->create([
//            'step_id' => $step->id,
//            'time' => floor($result->time() * 1000),
//            'request' => $test->getRequestAsArray(),
//            'response' => $test->getResponseAsArray(),
//        ]);
    }
}
