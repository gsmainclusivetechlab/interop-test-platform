<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestCaseStep extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_cases_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'request_validation',
        'response_validation',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(TestCase::class, 'case_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(TestUseCaseStep::class, 'step_id');
    }
}
