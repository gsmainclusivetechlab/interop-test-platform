<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SpecificationVersion extends Model
{
    use HasUuid;

    /**
     * @var array
     */
    protected $fillable = [
        'version',
        'schema',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'schema' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }
}
