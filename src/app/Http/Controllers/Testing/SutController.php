<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\BeforeSendingHandler;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Headers\TracestateHeader;
use App\Http\Middleware\SetContentLengthHeaders;
use App\Http\Middleware\SetJsonHeaders;
use App\Models\Component;
use App\Models\Session;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ServerRequestInterface;

class SutController extends Controller
{
    /**
     * RunController constructor.
     */
    public function __construct()
    {
        $this->middleware([SetJsonHeaders::class, SetContentLengthHeaders::class]);
    }

    /**
     * @param Session $session
     * @param Component $component
     * @param Component $connection
     * @param string $path
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(Session $session, Component $component, Component $connection, string $path, ServerRequestInterface $request)
    {
        $testStep = $session->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->whereRaw('JSON_CONTAINS(?, test_steps.trigger)', [$request->getBody()->getContents()])
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
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

        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withoutHeader(TracestateHeader::NAME)
            ->withUri(UriResolver::resolve(
                new Uri($session->getBaseUriOfComponent($testStep->target)),
                (new Uri($path))->withQuery((string) request()->getQueryString())
            ));

        return (new PendingRequest())
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->beforeSending(new BeforeSendingHandler($testResult))
            ->transfer($request)
            ->otherwise(new SendingRejectedHandler($testResult))
            ->wait();
    }
}
