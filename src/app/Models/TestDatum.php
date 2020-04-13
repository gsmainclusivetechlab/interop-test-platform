<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\TestRequestCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestDatum extends Model
{
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
        'request',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request' => TestRequestCast::class,
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
