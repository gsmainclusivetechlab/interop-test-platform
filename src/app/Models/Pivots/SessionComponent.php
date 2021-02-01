<?php

namespace App\Models\Pivots;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SessionComponent extends Pivot
{
    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
