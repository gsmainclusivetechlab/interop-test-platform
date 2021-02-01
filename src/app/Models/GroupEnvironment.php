<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
     * @var array
     */
    protected $attributes = [
        'variables' => '{}',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function fileEnvironments(): MorphMany
    {
        return $this->morphMany(FileEnvironment::class, 'environmentable');
    }
}
