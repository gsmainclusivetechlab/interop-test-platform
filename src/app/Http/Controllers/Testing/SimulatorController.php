<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\BeforeSendingHandler;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Component;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;

class SimulatorController extends Controller
{
    use HasRequest;

    /**
     * SimulatorController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class]);
        $this->middleware([ValidateTraceContext::class])->only(['simulator']);
    }

    public function __invoke(Component $component, Component $connection, string $path)
    {
        $request = $this->getRequest();
        $trace = new TraceparentHeader($request->getHeaderLine(TraceparentHeader::NAME));
        $testRun = TestRun::whereRaw('REPLACE(uuid, "-", "") = ?', $trace->getTraceId())->firstOrFail();
        $testStep = $testRun->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->offset(
                $testRun->testResults()
                    ->whereHas('testStep', function ($query) use ($component, $connection, $request, $path) {
                        $query->where('method', $request->getMethod())
                            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
                            ->whereHas('source', function ($query) use ($component) {
                                $query->whereKey($component->getKey());
                            })
                            ->whereHas('target', function ($query) use ($connection) {
                                $query->whereKey($connection->getKey());
                            });
                    })
                    ->count()
            )
            ->firstOrFail();

        $testResult = $testRun->testResults()->create(['test_step_id' => $testStep->id]);
        $uri = UriResolver::resolve(
            new Uri($testRun->session->getBaseUriOfComponent($testStep->target)),
            new Uri($path)
        );
        $request = $request->withUri($uri->withQuery((string) request()->getQueryString()));

        return (new PendingRequest())
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->beforeSending(new BeforeSendingHandler($testResult))
            ->transfer($request)
            ->otherwise(new SendingRejectedHandler($testResult))
            ->wait();
    }
}
