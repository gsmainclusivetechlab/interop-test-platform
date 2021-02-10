<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TestCaseComponents extends Pivot
{
    /** @var string[] */
    protected $casts = [
        'component_versions' => 'json',
    ];
}
