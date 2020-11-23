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
    protected $fillable = ['action', 'subject', 'type', 'meta'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fullname()
    {
        return $this->belongsTo(User::class, 'fullname');
    }

}
