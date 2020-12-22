<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

/**
 * Class Certificate
 *
 * @package App\Models
 *
 * @property int $id
 * @property int|null $group_id
 * @property string $name
 * @property string $path
 * @property Carbon $created_at
 *
 * @mixin \Eloquent
 */
class Certificate extends Model
{
    const UPDATED_AT = null;

    /** @var string[] */
    protected $fillable = ['name', 'path'];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected static function booted(): void
    {
        static::deleted(function (self $certificate) {
            Storage::delete($certificate->path);
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function getFullPath(): string
    {
        return Storage::path($this->path);
    }
}
