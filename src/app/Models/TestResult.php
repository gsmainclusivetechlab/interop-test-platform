<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestResult extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_results';

    /**
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function run()
    {
        return $this->belongsTo(TestRun::class, 'run_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(TestCaseStep::class, 'step_id');
    }

}
