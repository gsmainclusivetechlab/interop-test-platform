<?php

namespace App\Models\Pivots;

use App\Models\Session;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SessionTestCases extends Pivot
{
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
