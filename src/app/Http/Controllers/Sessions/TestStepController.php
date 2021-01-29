<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\{
    ComponentResource,
    SessionResource,
    TestCaseResource,
    TestStepResource,
};
use App\Models\Session;
use App\Models\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Inertia\Response;

class TestStepController extends Controller
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
    public function index(Session $session, TestCase $testCase)
    {
        $this->authorize('view', $session);

        $testCase = $session
            ->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testStepFirstSource = $testCase->testSteps()->firstOrFail()->source;

        return Inertia::render('sessions/test-steps/index', [
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
            'isAvailableRun' => $session->isAvailableTestCaseRun($testCase),
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource(
                $testStepFirstSource
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->paginate()
//                    ->map(function ($testStep) use ($simulatedTestResults) {
//                        $testStep->request = $simulatedTestResults[$testStep->id]->request;
//                        $testStep->response = $simulatedTestResults[$testStep->id]->response;
//
//                        return $testStep;
//                    })
            ),
            'simulatedTestResults' => $testCase->simulateTestResults($session),
        ]);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return Response
     */
    public function flow(Session $session, TestCase $testCase)
    {
        $testCase = $session
            ->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testStepFirstSource = $testCase->testSteps()->firstOrFail()->source;

        return Inertia::render('sessions/test-steps/flow', [
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
            'isAvailableRun' => $session->isAvailableTestCaseRun($testCase),
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource(
                $testStepFirstSource
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }
}
