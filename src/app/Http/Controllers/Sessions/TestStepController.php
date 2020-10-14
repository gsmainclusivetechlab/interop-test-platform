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
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::withTestCasesOfSession($session)->get()
            ),
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'testStepFirstSource' => (new ComponentResource(
                $testStepFirstSource
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->paginate()
            ),
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
                    'testCases' => function ($query) {
                        return $query->with(['useCase', 'lastTestRun']);
                    },
                    'components' => function ($query) {
                        return $query->with(['connections']);
                    },
                ])
            ))->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::withTestCasesOfSession($session)->get()
            ),
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
