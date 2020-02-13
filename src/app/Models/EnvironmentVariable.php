<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvironmentVariable extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }
}
