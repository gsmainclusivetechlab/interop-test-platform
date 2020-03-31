<?php

namespace App\View\Components\Charts;

use App\Models\Session;
use Illuminate\View\Component;

class LatestTestRuns extends Component
{
    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
//        dd($session);
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.charts.latest-test-runs');
    }
}
