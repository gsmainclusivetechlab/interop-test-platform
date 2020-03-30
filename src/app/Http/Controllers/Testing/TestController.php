<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends Controller
{
    /**
     * TestController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class, ValidateTraceContext::class]);
    }


    public function __invoke(ServerRequestInterface $request, string $uri)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()->offset($testRun->testResults()->count())->firstOrFail();
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        $uri = (new Uri($uri));
        $request = $request->withUri($uri);
    }
}
