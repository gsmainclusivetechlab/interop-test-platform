<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\{
    ComponentResource,
    SessionResource,
    TestCaseResource,
    TestResultResource,
    TestRunResource,
    TestStepResource
};
use App\Models\{Component, TestCase, Session, TestRun};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class TestRunController extends Controller
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
     * @param TestRun $testRun
     * @param int $position
     * @return Response
     * @throws AuthorizationException
     */
    public function show(
        Session $session,
        TestCase $testCase,
        TestRun $testRun,
        int $position = 1
    ) {
        $this->authorize('view', $session);

        $testCase = $session
            ->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        return Inertia::render('sessions/test-runs/show', [
            'session' => (new SessionResource(
                $session->load([
                    'components.connections',
                    'testCases' => function ($query) use ($session) {
                        return $query->with([
                            'useCase',
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ]);
                    },
                ])
            ))->resolve(),
            'isAvailableRun' => $session->isAvailableTestCaseRun($testCase),
            'components' => ComponentResource::collection(
                Component::with([
                    'connections' => function ($query) use ($testCase) {
                        $query
                            ->whereHas('sourceTestSteps', function (
                                $query
                            ) use ($testCase) {
                                $query->where('test_case_id', $testCase->id);
                            })
                            ->orWhereHas('targetTestSteps', function (
                                $query
                            ) use ($testCase) {
                                $query->where('test_case_id', $testCase->id);
                            });
                    },
                ])
                    ->whereHas('sourceTestSteps', function ($query) use (
                        $testCase
                    ) {
                        $query->where('test_case_id', $testCase->id);
                    })
                    ->orWhereHas('targetTestSteps', function ($query) use (
                        $testCase
                    ) {
                        $query->where('test_case_id', $testCase->id);
                    })
                    ->get()
            ),
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'testRun' => (new TestRunResource(
                $testRun->load([
                    'testResults' => function ($query) {
                        return $query->with(['testStep']);
                    },
                ])
            ))->resolve(),
            'testResults' => TestResultResource::collection(
                $testRun
                    ->testResults()
                    ->whereHas('testStep', function (Builder $query) use (
                        $position
                    ) {
                        $query->where('position', $position);
                    })
                    ->with(['testExecutions'])
                    ->get()
            )->resolve(),
            'testStep' => (new TestStepResource(
                $testCase
                    ->testSteps()
                    ->where('position', $position)
                    ->with(['source', 'target', 'testSetups'])
                    ->first()
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
