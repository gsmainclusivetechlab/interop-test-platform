<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Sessions\Traits\WithSutUrls;
use App\Http\Resources\{
    SessionResource,
    TestCaseResource,
    TestRunResource,
    TestStepResource,
};
use App\Jobs\ExecuteSessionTestCasesJob;
use App\Jobs\ExecuteTestRunJob;
use App\Models\TestCase;
use App\Models\Session;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseController extends Controller
{
    use WithSutUrls;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return Response
     * @throws AuthorizationException
     */
    public function show(Session $session, TestCase $testCase)
    {
        $this->authorize('view', $session);

        $testCase = $session
            ->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();

        return Inertia::render('sessions/test-cases/show', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) use ($session) {
                        return $query->with([
                            'useCase',
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ]);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'isAvailableRun' => $session->isAvailableTestCaseRun($testCase),
            'testRunAttempts' => config(
                'service_session.compliance_session_execution_limit'
            ),
            'testRuns' => TestRunResource::collection(
                $session
                    ->testRuns()
                    ->where('test_case_id', $testCase->id)
                    ->with(['session', 'testCase'])
                    //                    ->completed()
                    ->latest()
                    ->paginate()
            ),
            'sutUrls' => $this->getSutUrls($session),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function run(Session $session, TestCase $testCase)
    {
        $this->authorize('view', $session);

        abort_unless(
            $session->isAvailableTestCaseRun($testCase),
            403,
            __('Test case execution limit.')
        );

        $testRun = $session
            ->testRuns()
            ->create(['test_case_id' => $testCase->id]);

        $firstSourceId = $testCase->testSteps()->firstOrFail()->source->id;
        $firstSUT = $session
            ->components()
            ->whereKey($firstSourceId)
            ->wherePivotNull('implicit_sut_id')
            ->first();
        if (!$firstSUT) {
            ExecuteTestRunJob::dispatch($testRun)->afterResponse();
        }

        return redirect()
            ->route('sessions.test-cases.test-runs.show', [
                $session->id,
                $testCase->id,
                $testRun->id,
            ])
            ->with('success', __('Run started successfully'));
    }

    /**
     * @param Session $session
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function runAll(Session $session)
    {
        $this->authorize('view', $session);

        $testCases = $session->testCases()
            ->whereNotIn('id', $session->getFirstTestStepsWithSourceSut()->pluck('testCase.id'))
            ->get();

        $testCasesToExecute = [];
        foreach ($testCases as $testCase) {
            if (!$session->isAvailableTestCaseRun($testCase)) {
                continue;
            }
            $testCasesToExecute[] = $testCase->id;
        }

        ExecuteSessionTestCasesJob::dispatch($session, $testCasesToExecute)->afterResponse();

        return redirect()
            ->route('sessions.show', [$session->id])
            ->with('success', __('Run started successfully'));
    }
}
