<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $group_id
 * @property string $name
 * @property string $file_path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Group $group
 */
class SimulatorPlugin extends Model
{
    protected $fillable = ['name', 'file_path'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
