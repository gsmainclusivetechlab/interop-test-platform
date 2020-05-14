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

    public function sut(Session $session, Component $component, Component $connection, string $path)
    {
        $specification = $connection->pivot->specification;
        $pathFinder = new PathFinder($specification->openapi, new Uri("/{$path}"), $this->request->getMethod());
        $operationAddress = collect($pathFinder->search())->first();

        $testStep = $session->testSteps()
            ->where('path', $operationAddress->path())
            ->where('method', $operationAddress->method())
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->whereJsonContains('trigger', $this->request->getBody())
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereDoesntHave('testRuns', function ($query) {
                        $query->incompleted()->whereHas('testResults', function ($query) {
                            $query->whereColumn('test_step_id', '=', 'test_steps.id');
                        });
                    });
                })->orWhere(function ($query) {
                    $query->whereHas('testRuns', function ($query) {
                        $query->incompleted()->whereDoesntHave('testResults', function ($query) {
                            $query->whereColumn('test_step_id', '!=', 'test_steps.id');
                        });
                    });
                });
            })
            ->firstOrFail();

        $testRun = $session->testRuns()
            ->incompleted()
            ->where('test_case_id', $testStep->test_case_id)
            ->firstOrCreate(['test_case_id' => $testStep->test_case_id]);

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);

//        CompleteTestRunJob::dispatch($testRun)->delay(now()->addSeconds(30));

//        $baseUrl = $session->components()->whereKey($connection->getKey())->get('base_url') ?? $connection->base_url;

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $this->request->withUri(UriResolver::resolve(new Uri($connection->base_url), new Uri($path)))
            ->withAddedHeader(TraceparentHeader::NAME, (string) $traceparent);

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }

    public function simulator(/*Component $component, Component $connection, */string $path)
    {
        $trace = new TraceparentHeader($this->request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $trace->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()->offset($testRun->testResults()->count())->firstOrFail();
        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);
        $request = $this->request->withUri(UriResolver::resolve(new Uri($testStep->target->base_url), new Uri($path)));

        return (new PendingRequest($request))
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->send(new SendingFulfilledHandler($testResult), new SendingRejectedHandler($testResult));
    }
}
