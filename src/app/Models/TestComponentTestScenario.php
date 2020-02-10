<?php

namespace App\Models;

use App\Models\Concerns\HasPosition;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TestComponentTestScenario extends Pivot
{
    use HasPosition;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return array|string
     */
    public function getPositionGroupColumn()
    {
        return ['test_scenario_id'];
    }
}
