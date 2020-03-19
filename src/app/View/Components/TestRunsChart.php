<?php

namespace App\View\Components;

use App\Models\Session;
use Illuminate\View\Component;

class TestRunsChart extends Component
{
    /**
     * @var array
     */
    public $chartSeries = [];

    /**
     * @var array
     */
    public $chartOptions = [];

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
        return view('components.test-runs-chart');
    }
}
