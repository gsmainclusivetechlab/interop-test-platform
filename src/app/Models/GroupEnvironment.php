<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class GroupEnvironment extends Model
{
    /**
     * @var string
     */
    protected $table = 'group_environments';

    /**
     * @var array
     */
    protected $fillable = ['name', 'variables'];

    /**
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
