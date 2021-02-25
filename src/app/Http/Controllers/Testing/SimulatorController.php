<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Exceptions\MessageMismatchException;
use App\Http\Headers\TraceparentHeader;
use App\Http\Middleware\ValidateTraceContext;
use App\Models\Component;
use App\Models\TestRun;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ServerRequestInterface;

class SimulatorController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(ValidateTraceContext::class);
    }

    /**
     * @param string $componentSlug
     * @param string $connectionSlug
     * @param string $path
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(
        string $componentSlug,
        string $connectionSlug,
        string $path,
        ServerRequestInterface $request
    ) {
        $component = $this->getComponent($componentSlug);
        $connection = $this->getComponent($connectionSlug);

        $trace = new TraceparentHeader(
            $request->getHeaderLine(TraceparentHeader::NAME)
        );
        $testRun = TestRun::whereRaw(
            'REPLACE(uuid, "-", "") = ?',
            $trace->getTraceId()
        )->firstOrFail();
        $session = $testRun->session;

        abort_unless(
            $session->isAvailableToUpdate(),
            403,
            __('Session not available to update')
        );

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

        if (!$testStep) {
            throw new MessageMismatchException(
                $session,
                404,
                'Unable to match simulator request with an awaited test step. Please check the test preconditions.'
            );
        }

        $testResult = $testRun->createTestResult($testStep);
        $request = $request->withUri(
            UriResolver::resolve(
                new Uri(
                    ($uri = $session->getBaseUriOfComponent(
                        $connection,
                        null,
                        true
                    ))
                ),
                new Uri($path)
            )->withQuery((string) request()->getQueryString())
        );

        return (new ProcessPendingRequest(
            $request,
            $testResult,
            $session,
            empty($uri)
        ))();
    }

    protected function getComponent(string $componentSlug): Component
    {
        if (!($component = Component::where('slug', $componentSlug)->first())) {
            throw new MessageMismatchException(
                null,
                404,
                "Unable to find test component with id $componentSlug. Please check the request base URL"
            );
        }

        return $component;
    }
}
