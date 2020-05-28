<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\RequestCast;
use App\Casts\ResponseCast;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestLog extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'request',
        'status_code',
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
