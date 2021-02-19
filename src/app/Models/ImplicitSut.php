<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $slug
 * @property string $version
 * @property string $url
 * @property bool $use_encryption
 *
 * @property Certificate $certificate
 */
class ImplicitSut extends Model
{
    protected $fillable = ['slug', 'version', 'url', 'use_encryption'];

    protected $casts = [
        'use_encryption' => 'boolean',
    ];

    public static function booted()
    {
        static::deleted(function (self $implicitSut) {
            if ($implicitSut->certificate) {
                $implicitSut->certificate->delete();
            }
        });
    }

    public function certificate(): MorphOne
    {
        return $this->morphOne(Certificate::class, 'certificable');
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(
            Session::class,
            'session_components',
            'implicit_sut_id',
            'session_id'
        );
    }

    public function hasSessions(): bool
    {
        return $this->sessions()->exists();
    }
}
