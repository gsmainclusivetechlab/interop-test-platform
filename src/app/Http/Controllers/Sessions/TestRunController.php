<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestResultResource;
use App\Http\Resources\TestRunResource;
use App\Http\Resources\TestStepResource;
use App\Models\Component;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
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
        $testStepFirstSource = $testCase->testSteps()->firstOrFail()->source;

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
            'testStepFirstSource' => (new ComponentResource(
                $testStepFirstSource
            ))->resolve(),
            'testRun' => (new TestRunResource(
                $testRun->load([
                    'testResults' => function ($query) {
                        return $query->with(['testStep']);
                    },
                ])
            ))->resolve(),
            'testResult' => (new TestResultResource(
                $testRun
                    ->testResults()
                    ->whereHas('testStep', function (Builder $query) use (
                        $position
                    ) {
                        $query->where('position', $position);
                    })
                    ->with([
                        'testStep' => function ($query) {
                            return $query->with([
                                'source',
                                'target',
                                'testSetups',
                            ]);
                        },
                        'testExecutions',
                    ])
                    ->firstOrFail()
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
