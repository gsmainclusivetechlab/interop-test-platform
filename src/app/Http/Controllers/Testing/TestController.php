<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\ApiService;
use App\Models\TestRun;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class TestController extends Controller
{
    /**
     * TestController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class, ValidateTraceContext::class]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ApiService $specification
     * @param string $path
     * @return \Exception|AssertionFailedError|ResponseInterface|Throwable
     */
    public function __invoke(ServerRequestInterface $request, ApiService $specification, string $path)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())
            ->firstOrFail();

        return 1;

        $testStep = $testRun->testSteps()
            ->offset($testRun->testResults()->count())
            ->firstOrFail();

        $testResult = $testRun->testResults()->make([
            'source_id' => $testStep->source_id,
            'target_id' => $testStep->target_id,
            'request' => [],
            'response' => [],
            'total' => 0,
            'passed' => 0,
            'errors' => 0,
            'failures' => 0,
            'time' => 0,
        ]);

        $uri = (new Uri($testStep->target->apiService->server))
            ->withPath($path);
        $request = $request->withUri($uri);

        $response = (new Client(['http_errors' => false]))->send($request);
        $testResult->save();

        return 1;
        dd(1);

        return $this->doTest($request, $testRun, $testStep);
    }
}
