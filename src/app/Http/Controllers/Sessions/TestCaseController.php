<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Session $session, TestCase $testCase)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testRuns = $session->testRuns()
            ->where('test_case_id', $testCase->id)
            ->latest()
            ->paginate();

        return view('sessions.test-cases.show', compact('session', 'testCase', 'testRuns'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function flow(Session $session, TestCase $testCase)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();

        return view('sessions.test-cases.flow', compact('session', 'testCase'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param TestRun $testRun
     * @param int $position
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function results(Session $session, TestCase $testCase, TestRun $testRun, int $position = 1)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testResult = $testRun->testResults()
            ->whereHas('testStep', function (Builder $query) use ($position) {
                $query->where('position', $position);
            })
            ->firstOrFail();

        return view('sessions.test-cases.results', compact('session', 'testCase', 'testRun', 'testResult'));
    }
}
