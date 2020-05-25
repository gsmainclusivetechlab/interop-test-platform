<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestResultResource;
use App\Http\Resources\TestRunResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Component;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
use App\Models\UseCase;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class TestRunController extends Controller
{
    /**
     * TestRunController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Session $session, TestCase $testCase)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/test-runs/index', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use($session) {
                        $query->with([
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ])->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    }])
                    ->whereHas('testCases', function ($query) use($session) {
                        $query->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    })
                    ->get()
            ),
            'testCase' => (new TestCaseResource(
                $session->testCases()
                    ->where('test_case_id', $testCase->id)
                    ->firstOrFail()
            ))->resolve(),
            'testRuns' => TestRunResource::collection(
                $session->testRuns()
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
     * @param TestRun $testRun
     * @param int $position
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Session $session, TestCase $testCase, TestRun $testRun, int $position = 1)
    {
        $this->authorize('view', $session);

        return Inertia::render('sessions/test-runs/show', [
            'session' => (new SessionResource(
                $session->load([
                    'components',
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) use($session) {
                        $query->with([
                            'lastTestRun' => function ($query) use ($session) {
                                $query->where('session_id', $session->id);
                            },
                        ])->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    }])
                    ->whereHas('testCases', function ($query) use($session) {
                        $query->whereHas('sessions', function ($query) use($session) {
                            $query->whereKey($session->getKey());
                        });
                    })
                    ->get()
            ),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'testCase' => (new TestCaseResource(
                $session->testCases()
                    ->where('test_case_id', $testCase->id)
                    ->firstOrFail()
                    ->load(['testSteps'])
            ))->resolve(),
            'testRun' => (new TestRunResource(
                $testRun->load([
                    'testResults' => function ($query) {
                        return $query->with(['testStep']);
                    },
                ])
            ))->resolve(),
            'testResult' => (new TestResultResource(
                $testRun->testResults()
                    ->whereHas('testStep', function (Builder $query) use ($position) {
                        $query->where('position', $position);
                    })
                    ->with([
                        'testStep' => function ($query) {
                            return $query->with(['source', 'target', 'testSetups']);
                        },
                        'testExecutions',
                    ])
                    ->firstOrFail()
            ))->resolve(),
        ]);
    }
}
