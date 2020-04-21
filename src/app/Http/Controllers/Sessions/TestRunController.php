<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Http\Resources\TestCaseResource;
use App\Http\Resources\TestResultResource;
use App\Http\Resources\TestRunResource;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestRun;
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
     * @param TestRun $testRun
     * @param int $position
     * @return \Inertia\Response
     */
    public function show(Session $session, TestCase $testCase, TestRun $testRun, int $position = 1)
    {
        return Inertia::render('sessions/test-runs/show', [
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
            'testRun' => (new TestRunResource($testRun))->resolve(),
            'testResult' => (new TestResultResource(
                $testRun->testResults()
                    ->whereHas('testStep', function (Builder $query) use ($position) {
                        $query->where('position', $position);
                    })
                    ->firstOrFail()
            ))->resolve(),
        ]);
    }
}
