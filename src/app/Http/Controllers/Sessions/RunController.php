<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\TestRun;
use App\Models\TestSession;

class RunController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show(TestSession $session, TestCase $case, TestRun $run)
    {
        $case = $session->cases()->where('case_id', $case->id)->firstOrFail();

        return view('sessions.cases.runs.show', compact('session', 'case', 'run'));
    }
}
