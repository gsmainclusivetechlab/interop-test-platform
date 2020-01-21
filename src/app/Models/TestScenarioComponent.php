<?php

namespace App\Models;

use App\Models\Traits\HasPosition;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TestScenarioComponent extends Pivot
{
    use HasPosition;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'test_scenarios_components';

    /**
     * @return array|string
     */
    public function getPositionGroupColumn()
    {
        return ['test_scenario_id'];
    }
}
