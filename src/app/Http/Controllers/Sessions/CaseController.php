<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
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

    public function show(TestSession $session, int $case)
    {
        $case = $session->cases()->where('case_id', $case)->firstOrFail();

        return view('sessions.cases.show', compact('session', 'case'));
    }
}
