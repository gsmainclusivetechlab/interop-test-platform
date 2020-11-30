<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestRunResource;
use App\Jobs\ExecuteTestRunJob;
use App\Models\TestCase;
use App\Models\Session;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseController extends Controller
{
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
        $testStepFirstSource = $testCase->testSteps()->firstOrFail()->source;

        return Inertia::render('sessions/test-cases/show', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource(
                $testStepFirstSource
            ))->resolve(),
            'isAvailableRun' => $session->isAvailableTestCaseRun($testCase),
            'testRunAttempts' => config(
                'test_cases.compliance_session_execution_limit'
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

        ExecuteTestRunJob::dispatch($session, $testCase)->afterResponse();

        return redirect()
            ->back()
            ->with('success', __('Run started successfully'));
    }
}
