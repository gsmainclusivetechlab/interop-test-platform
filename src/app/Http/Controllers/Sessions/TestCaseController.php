<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestRunResource;
use App\Models\TestCase;
use App\Models\Session;
use Inertia\Inertia;

class TestCaseController extends Controller
{
    /**
     * TestCaseController constructor.
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
    public function show(Session $session, TestCase $testCase)
    {
        return Inertia::render('sessions/test-cases/show', [
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
            'testRuns' => TestRunResource::collection(
                $session->testRuns()
                    ->where('test_case_id', $testCase->id)
                    ->with(['session', 'testCase'])
                    ->latest()
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
        return Inertia::render('sessions/test-cases/flow', [
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
        ]);
    }
}
