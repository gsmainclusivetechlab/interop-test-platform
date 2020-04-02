<?php

namespace App\View\Components\Sessions;

use App\Models\Session;
use Illuminate\View\Component;

class LatestTestRunsProgress extends Component
{
    /**
     * @var Session
     */
    public $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.sessions.latest-test-runs-progress');
    }

    /**
     * @return array
     */
    public function progressData()
    {
        $totalCount = $this->session->testCases()->count();
        $passedCount = $this->session->testRuns()->latest()->completed()->get()->unique('test_case_id')->where('successful', true)->count();
        $failureCount = $this->session->testRuns()->latest()->completed()->get()->unique('test_case_id')->where('successful', false)->count();
        $notExecutedCount = $totalCount - $passedCount - $failureCount;

        return [
            [
                'value' => $totalCount ? $passedCount / $totalCount * 100 : 0,
                'variant' => 'success',
                'title' => __(':n Pass', ['n' => $passedCount]),
            ],
            [
                'value' => $totalCount ? $failureCount / $totalCount * 100 : 0,
                'variant' => 'danger',
                'title' => __(':n Fail', ['n' => $failureCount]),
            ],
            [
                'value' => $totalCount ? $notExecutedCount / $totalCount * 100 : 0,
                'variant' => 'secondary',
                'title' => __(':n Not executed', ['n' => $notExecutedCount]),
            ],
        ];
    }
}
