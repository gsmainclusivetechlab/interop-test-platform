<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin \Eloquent
 */
class ComponentPath extends Pivot
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'component_paths';

    /**
     * @var array
     */
    protected $fillable = [
        'api_scheme_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apiScheme()
    {
        return $this->belongsTo(ApiScheme::class, 'api_scheme_id');
    }
}
