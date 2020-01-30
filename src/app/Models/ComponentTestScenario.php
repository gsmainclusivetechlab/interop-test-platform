<?php

namespace App\Models;

use App\Models\Traits\HasPosition;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ComponentTestScenario extends Pivot
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
