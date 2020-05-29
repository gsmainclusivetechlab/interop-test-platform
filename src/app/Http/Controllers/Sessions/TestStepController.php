<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestStepResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\UseCase;
use Inertia\Inertia;

class TestStepController extends Controller
{
    /**
     * TestStepController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Inertia\Response
     */
    public function index(Session $session, TestCase $testCase)
    {
        $this->authorize('view', $session);

        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testStepFirstSource = $testCase->testSteps()
            ->firstOrFail()
            ->source;

        return Inertia::render('sessions/test-steps/index', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
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
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource($testStepFirstSource))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase->testSteps()
                    ->with(['source', 'target'])
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Inertia\Response
     */
    public function flow(Session $session, TestCase $testCase)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testStepFirstSource = $testCase->testSteps()
            ->firstOrFail()
            ->source;

        return Inertia::render('sessions/test-steps/flow', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
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
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource($testStepFirstSource))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }
}
