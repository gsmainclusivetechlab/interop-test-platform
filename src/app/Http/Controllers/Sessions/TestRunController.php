<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
use Illuminate\Database\Eloquent\Builder;

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
     * @param TestRun $testRun
     * @param int $position
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Session $session, TestCase $testCase, TestRun $testRun, int $position = 1)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testResult = $testRun->testResults()
            ->whereHas('testStep', function (Builder $query) use ($position) {
                $query->where('position', $position);
            })
            ->firstOrFail();

        return view('sessions.test-runs.show', compact('session', 'testCase', 'testRun', 'testResult'));
    }
}
