<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Exceptions\MessageMismatchException;
use App\Http\Headers\TraceparentHeader;
use App\Http\Headers\TracestateHeader;
use App\Models\Component;
use App\Models\Group;
use App\Models\Session;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\ServerRequestInterface;

class SutController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (request()->header('Accept') == '*/*') {
            request()->headers->set('Accept', 'application/json');
        }
    }

    /**
     * @param Session $session
     * @param string $componentSlug
     * @param string $connectionSlug
     * @param string $path
     * @param ServerRequestInterface $request
     *
     * @return mixed
     */
    public function testingSession(
        string $componentSlug,
        string $connectionSlug,
        Session $session,
        string $path,
        ServerRequestInterface $request
    ) {
        return $this->testing(
            $session,
            $componentSlug,
            $connectionSlug,
            $path,
            $request
        );
    }

    /**
     * @param Group $group
     * @param string $componentSlug
     * @param string $connectionSlug
     * @param string $path
     * @param ServerRequestInterface $request
     *
     * @return mixed
     */
    public function testingGroup(
        string $componentSlug,
        string $connectionSlug,
        Group $group,
        string $path,
        ServerRequestInterface $request
    ) {
        abort_if(
            !($session = $group->defaultSession),
            404,
            __(
                'No default session has been configured for the specified group.'
            )
        );

        return $this->testing(
            $session,
            $componentSlug,
            $connectionSlug,
            $path,
            $request
        );
    }

    /**
     * @param Session $session
     * @param string $componentSlug
     * @param string $connectionSlug
     * @param string $path
     * @param ServerRequestInterface $request
     *
     * @return mixed
     */
    protected function testing(
        Session $session,
        string $componentSlug,
        string $connectionSlug,
        string $path,
        ServerRequestInterface $request
    ) {
        $component = $this->getComponent($componentSlug, $session);
        $connection = $this->getComponent($connectionSlug, $session);

        $testStep = $session
            ->testSteps()
            ->where('method', $request->getMethod())
            ->whereRaw('REGEXP_LIKE(?, pattern)', [$path])
            ->where(function ($query) use ($request) {
                $query->whereNull('test_steps.trigger');
                $query->orWhereRaw('JSON_CONTAINS(?, test_steps.trigger)', [
                    json_encode($request->getParsedBody()),
                ]);
            })
            ->whereHas('source', function ($query) use ($component) {
                $query->whereKey($component->getKey());
            })
            ->whereHas('target', function ($query) use ($connection) {
                $query->whereKey($connection->getKey());
            })
            ->where(function ($query) {
                $query
                    ->where(function ($query) {
                        $query->where('position', '=', 1);
                        $query->whereDoesntHave('testRuns', function ($query) {
                            $query
                                ->incompleted()
                                ->whereDoesntHave('testResults', function (
                                    $query
                                ) {
                                    $query->whereColumn(
                                        'test_step_id',
                                        'test_steps.id'
                                    );
                                });
                        });
                    })
                    ->orWhere(function ($query) {
                        $query->where('position', '!=', 1);
                        $query->whereHas('testRuns', function ($query) {
                            $query
                                ->incompleted()
                                ->whereDoesntHave('testResults', function (
                                    $query
                                ) {
                                    $query->whereColumn(
                                        'test_step_id',
                                        'test_steps.id'
                                    );
                                });
                        });
                    });
            })
            ->first();

        if (!$testStep) {
            throw new MessageMismatchException(
                $session,
                404,
                'Unable to match simulator request with an awaited test step. Please check the test preconditions.'
            );
        }

        if ($session->isComplianceSession()) {
            $testRunsCount = $session
                ->testRuns()
                ->where('test_case_id', $testStep->test_case_id)
                ->count();

            $message = !$session->isAvailableToUpdate()
                ? 'Session not available to update'
                : 'You have reached the limit of :limit allowed test runs per test case.';

            $limit = config(
                'service_session.compliance_session_execution_limit'
            );

            abort_if(
                !$session->isAvailableToUpdate() || $testRunsCount >= $limit,
                403,
                __($message, ['limit' => $limit])
            );
        }

        $testRun = $session
            ->testRuns()
            ->incompleted()
            ->where('test_case_id', $testStep->test_case_id)
            ->firstOrCreate(['test_case_id' => $testStep->test_case_id]);
        $testResult = $testRun
            ->testResults()
            ->create(['test_step_id' => $testStep->id]);
        $traceparent = (new TraceparentHeader())
            ->withTraceId($testRun->trace_id)
            ->withVersion(TraceparentHeader::DEFAULT_VERSION);
        $request = $request
            ->withHeader(TraceparentHeader::NAME, (string) $traceparent)
            ->withoutHeader(TracestateHeader::NAME)
            ->withUri(
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

    /**
     * @param string $componentSlug
     * @param Session $session
     *
     * @return Component
     */
    protected function getComponent(string $componentSlug, Session $session)
    {
        if (
            ($component = Component::where('slug', $componentSlug)->first()) ==
            null
        ) {
            throw new MessageMismatchException(
                $session,
                404,
                "Unable to find test component with id $componentSlug. Please check the request base URL"
            );
        }

        return $component;
    }
}
