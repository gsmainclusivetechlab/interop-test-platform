<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class TestOperationStep extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_operations_steps';

    /**
     * @var array
     */
    protected $fillable = [
        'path',
        'method',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo(TestOperation::class, 'operation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connection()
    {
        return $this->belongsTo(TestPlatformConnection::class, 'connection_id');
    }
}
