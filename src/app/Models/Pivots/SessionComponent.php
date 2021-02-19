<?php

namespace App\Models\Pivots;

use App\Models\Certificate;
use App\Models\ImplicitSut;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read string $base_url
 * @property-read bool $use_encryption
 *
 * @property Certificate $certificate
 * @property ImplicitSut $implicitSut
 */
class SessionComponent extends Pivot
{
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function implicitSut(): BelongsTo
    {
        return $this->belongsTo(ImplicitSut::class);
    }

    public function getBaseUrlAttribute(): string
    {
        return $this->implicitSut->url ?? $this->getRawOriginal('base_url');
    }

    public function getUseEncryptionAttribute(): bool
    {
        return $this->implicitSut->use_encryption ??
            $this->getRawOriginal('use_encryption');
    }
}
