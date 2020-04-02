<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\InteractsWithHttpRequest;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestDatum extends Model
{
    use InteractsWithHttpRequest;

    /**
     * @var string
     */
    protected $table = 'test_data';

    /**
     * @var array
     */
    protected $fillable = [
        'test_case_id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function testCase()
    {
        return $this->belongsTo(TestCase::class, 'test_case_id');
    }
}
