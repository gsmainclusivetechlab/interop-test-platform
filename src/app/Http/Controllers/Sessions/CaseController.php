<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\TestCase;
use App\Models\TestSession;

class CaseController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show(TestSession $session, TestCase $case)
    {
        $case = $session->cases()->where('case_id', $case->id)->firstOrFail();
        $runs = $session->runs()->with('case', 'session')->where('case_id', $case->id)->latest()->paginate();

        return view('sessions.cases.show', compact('session', 'case', 'runs'));
    }
}
