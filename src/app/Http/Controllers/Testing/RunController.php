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
use App\Jobs\CompleteTestRunJob;
use App\Models\Component;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\TestRun;
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
        $this->middleware([ValidateTraceContext::class])->only(['simulator']);
    }

    public function run(Session $session, TestCase $testCase, string $path)
    {
        $testStep = $testCase->testSteps()->firstOrFail();
        $testRun = $session->testRuns()->create(['test_case_id' => $testStep->test_case_id]);
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

        if ($sut = $session->components()->whereKey($testStep->target->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $testStep->target->base_url;
        }

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $this->request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)))
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }

    public function sut(Session $session, Component $component, Component $connection, string $path)
    {
        if ($sut = $session->components()->whereKey($connection->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $connection->base_url;
        }

        $request = $this->request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)));

        $specification = $connection->pivot->specification;
        $pathFinder = new PathFinder($specification->openapi, $request->getUri(), $request->getMethod());

        if (($operationAddress = collect($pathFinder->search())->first()) == null) {
            abort(404);
        }

        $testStep = $session->testSteps()
            ->where('path', $operationAddress->path())
            ->where('method', $operationAddress->method())
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->whereRaw('JSON_CONTAINS(?, test_steps.trigger)', [$request->getBody()->getContents()])
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('position', '=', 1);
                    $query->whereDoesntHave('testRuns', function ($query) {
                        $query->incompleted();
                    });
                })->orWhere(function ($query) {
                    $query->where('position', '!=', 1);
                    $query->whereHas('testRuns', function ($query) {
                        $query->incompleted();
                    });
                });
            })
            ->firstOrFail();

        $testRun = $session->testRuns()
            ->incompleted()
            ->where('test_case_id', $testStep->test_case_id)
            ->firstOrCreate(['test_case_id' => $testStep->test_case_id]);

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        if ($testStep->isFirstPosition()) {
            CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));
        }

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withHeader(TraceparentHeader::NAME, (string) $traceparent);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }

    public function simulator(Component $component, Component $connection, string $path)
    {
        $trace = new TraceparentHeader($this->request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $trace->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->offset(
                $testRun->testResults()
                    ->whereHas('testStep', function ($query) use ($component, $connection) {
                        $query->whereHas('source', function ($query) use ($component) {
                            $query->whereKey($component->getKey());
                        })->whereHas('target', function ($query) use ($connection) {
                            $query->whereKey($connection->getKey());
                        });
                    })
                    ->count()
            )
            ->firstOrFail();

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

        if ($sut = $testRun->session->components()->whereKey($connection->getKey())->first()) {
            $url = $sut->pivot->base_url;
        } else {
            $url = $testStep->target->base_url;
        }

        $request = $this->request->withUri(UriResolver::resolve(new Uri($url), new Uri($path)));

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }
}
