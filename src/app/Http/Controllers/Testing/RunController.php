<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Jobs\ProcessTimeoutTestRun;
use App\Models\TestPlan;
use App\Models\TestRun;
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
    public function __invoke(ServerRequestInterface $request, TestPlan $plan, string $path)
    {
        $step = $plan->steps()->firstOrFail();
        $run = TestRun::create([
            'case_id' => $plan->case_id,
            'session_id' => $plan->session_id,
        ]);

//        ProcessTimeoutTestRun::dispatch($run)
//            ->delay(now()->addMinutes(1));

        $uri = (new Uri($step->platform->server))
            ->withPath($path);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($run->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withUri($uri)
            ->withMethod($request->getMethod())
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        return $this->doTest($request, $run, $step);
    }
}
