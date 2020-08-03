<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Exceptions\MessageMismatchException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\SetContentLengthHeaders;
use App\Http\Middleware\SetJsonHeaders;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Component;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ServerRequestInterface;

class SimulatorController extends Controller
{
    /**
     * SimulatorController constructor.
     */
    public function __construct()
    {
        $this->middleware([
            SetJsonHeaders::class,
            SetContentLengthHeaders::class,
            ValidateTraceContext::class,
        ]);
    }

    /**
     * @param Component $component
     * @param Component $connection
     * @param string $path
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(
        Component $component,
        Component $connection,
        string $path,
        ServerRequestInterface $request
    ) {
        $trace = new TraceparentHeader(
            $request->getHeaderLine(TraceparentHeader::NAME)
        );
        $testRun = TestRun::whereRaw(
            'REPLACE(uuid, "-", "") = ?',
            $trace->getTraceId()
        )->firstOrFail();
        $session = $testRun->session;

        $testStep = $testRun
            ->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->offset(
                $testRun
                    ->testResults()
                    ->whereHas('testStep', function ($query) use (
                        $component,
                        $connection,
                        $request,
                        $path
                    ) {
                        $query
                            ->where('method', $request->getMethod())
                            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
                            ->whereHas('source', function ($query) use (
                                $component
                            ) {
                                $query->whereKey($component->getKey());
                            })
                            ->whereHas('target', function ($query) use (
                                $connection
                            ) {
                                $query->whereKey($connection->getKey());
                            });
                    })
                    ->count()
            )
            ->first();

        if ($testStep === null) {
            throw new MessageMismatchException(
                $testRun->session,
                404,
                'Unable to match simulator request with an awaited test step. Please check the test preconditions.'
            );
        }

        $testResult = $testRun
            ->testResults()
            ->create(['test_step_id' => $testStep->id]);
        $request = $request->withUri(
            UriResolver::resolve(
                new Uri($session->getBaseUriOfComponent($connection)),
                new Uri($path)
            )->withQuery((string) request()->getQueryString())
        );

        return (new PendingRequest())
            ->mapRequest(new MapRequestHandler($testResult))
            ->mapResponse(new MapResponseHandler($testResult))
            ->transfer($request)
            ->then(new SendingFulfilledHandler($testResult))
            ->otherwise(new SendingRejectedHandler($testResult))
            ->wait();
    }
}
