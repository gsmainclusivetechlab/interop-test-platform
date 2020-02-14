<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestRun extends Model
{
    use HasUuid;

    /**
     * @var string
     */
    protected $table = 'test_runs';

    /**
     * @var array
     */
    protected $fillable = [

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
    public function session()
    {
        return $this->belongsTo(TestSession::class, 'session_id');
    }

}
