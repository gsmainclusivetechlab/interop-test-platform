<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class AuditLog extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'audit_log';

    /**
     * @var array
     */
    protected $casts = [
        'meta' => 'array'
    ];

    /**
     * @var array
     */
    protected $fillable = ['action', 'subject', 'type', 'meta'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
