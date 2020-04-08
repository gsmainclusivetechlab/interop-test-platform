<?php

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpResponse;
use Illuminate\Database\Eloquent\Model;

class TestResponseExample extends Model
{
    use InteractsWithHttpResponse;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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