<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\TestCase;

/**
 * Class TestStepController
 *
 * @package App\Http\Controllers\Sessions
 */
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
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Session $session, TestCase $testCase)
    {
        $testSteps = $testCase->testSteps()->paginate();

        return view('sessions.test-steps.index', compact('session', 'testCase', 'testSteps'));
    }
}