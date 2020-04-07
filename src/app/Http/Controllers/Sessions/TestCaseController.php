<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\Session;

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
}
