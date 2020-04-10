<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpRequest;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TestRequestExample
 *
 * @package App\Models
 */
class TestRequestExample extends Model
{
    use InteractsWithHttpRequest;

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
