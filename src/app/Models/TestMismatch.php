<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestMismatch extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_mismatches';

    /**
     * @var array
     */
    protected $fillable = [
        'request',
        'exception',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'request' => RequestCast::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
