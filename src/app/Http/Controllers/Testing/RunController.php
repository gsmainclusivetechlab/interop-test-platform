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
use App\Models\Component;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\TestStep;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use League\OpenAPIValidation\PSR7\PathFinder;
use Psr\Http\Message\ServerRequestInterface;

class RunController extends Controller
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * RunController constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->middleware([SetJsonHeaders::class]);
    }

    public function __invoke(Component $component, Component $connection, Session $session, string $path)
    {
        $request = $this->request->withUri(UriResolver::resolve(new Uri('http://172.16.14.103:8084'), new Uri($path)));
        $specification = $connection->pivot->specification;
        $pathFinder = new PathFinder($specification->openapi, $request->getUri(), $request->getMethod());
        $operationAddress = collect($pathFinder->search())->first();

        dd($request->getUri()->getPath());
        dd($operationAddress->method());

        $testStep = $session->testSteps()
            ->where('path', $operationAddress->path())
            ->where('method', $operationAddress->method())
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->firstOrFail();

        dd($testStep);

//        $testRun = tap($session->testRuns()->make(), function ($testRun) use ($testCase) {
//            $testRun->testCase()
//                ->associate($testCase)
//                ->save();
//        });
//        $testRun->increment('total', $testCase->testSteps()->count());
//        $testStep = $testRun->testCase->testSteps()->firstOrFail();
//        $testResult = tap($testRun->testResults()->make(), function ($testResult) use ($testStep) {
//            $testResult->testStep()
//                ->associate($testStep)
//                ->save();
//        });
//
//        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));
//
//        $baseUrl = $session->suts()->whereKey($testStep->target->id)->value('base_url')
//            ??
//            $testStep->target->apiService->base_url;
//        $traceparent = (new TraceparentHeader())
//            ->withTraceId($testRun->trace_id)
//            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
//        $request = $request->withUri(UriResolver::resolve(new Uri($baseUrl), new Uri($path)))
//            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);
//
//        return (new PendingRequest($request))
//            ->mapRequest(new MapRequestHandler($testResult))
//            ->mapResponse(new MapResponseHandler($testResult))
//            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }
}
