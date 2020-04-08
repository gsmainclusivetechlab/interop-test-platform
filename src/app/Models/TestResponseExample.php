<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpResponse;
use Illuminate\Database\Eloquent\Model;

class TestResponseExample extends Model
{
    use InteractsWithHttpResponse;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testStep()
    {
        return $this->belongsTo(TestStep::class, 'test_step_id');
    }
}