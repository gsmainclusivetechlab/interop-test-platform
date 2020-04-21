<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestStepResource;
use App\Models\Session;
use App\Models\TestCase;
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
        return Inertia::render('sessions/test-steps/index', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                ])
            ))->resolve(),
            'testCase' => (new TestCaseResource(
                $session->testCases()
                    ->where('test_case_id', $testCase->id)
                    ->firstOrFail()
            ))->resolve(),
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
        return Inertia::render('sessions/test-steps/flow', [
            'session' => (new SessionResource(
                $session->load([
                    'testCases' => function ($query) {
                        return $query->with(['lastTestRun']);
                    },
                ])
            ))->resolve(),
            'testCase' => (new TestCaseResource(
                $session->testCases()
                    ->where('test_case_id', $testCase->id)
                    ->firstOrFail()
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }
}
