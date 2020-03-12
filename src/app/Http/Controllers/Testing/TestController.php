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
    public function __invoke(ServerRequestInterface $request, ApiService $apiService, string $path)
    {
        $traceparent = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $traceparent->getTraceId())
            ->whereNull('completed_at')
            ->firstOrFail();

//        $testStep = $testRun->testSteps()
//            ->offset($testRun->testResults()->count())
//            ->firstOrFail();

//        $testStep = $testRun->testSteps()
//            ->whereHas('source', function ($query) use ($authority) {
//                $query->whereHas('apiService', function ($query) use ($authority) {
//                    $query->where('server', 'like', "%{$authority}");
//                });
//            })
//            ->offset($testRun->testResults()
//                ->whereHas('testStep', function ($query) use ($authority) {
//                    $query->whereHas('source', function ($query) use ($authority) {
//                        $query->whereHas('apiService', function ($query) use ($authority) {
//                            $query->where('server', "%{$authority}");
//                        });
//                    });
//                })->count())
//            ->firstOrFail();

        $testStep = $testRun->testSteps()
            ->whereHas('target', function ($query) use ($apiService) {
                $query->where('api_service_id', $apiService->id);
            })
            ->offset($testRun->testResults()
                ->whereHas('testStep', function ($query) use ($apiService) {
                    $query->whereHas('target', function ($query) use ($apiService) {
                        $query->where('api_service_id', $apiService->id);
                    });
                })->count())
            ->firstOrFail();

        $uri = (new Uri($testStep->target->apiService->server))->withPath($path);
        $request = $request->withUri($uri);
        $response = (new Client(['http_errors' => false]))->send($request);

        $testResult = $testRun->testResults()->create([
            'test_step_id' => $testStep->id,
        ]);

        return $this->doTest($testResult);
    }
}
