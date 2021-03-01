<?php

namespace App\Models\Pivots;

use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ComponentConnections extends Pivot
{
    public function source(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
