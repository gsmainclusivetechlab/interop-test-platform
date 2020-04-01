<?php

namespace App\View\Components\Charts;

use App\Models\Session;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\View\Component;

class LatestTestRuns extends Component implements Arrayable
{
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
        return view('components.charts.latest-test-runs');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [];
    }
}
