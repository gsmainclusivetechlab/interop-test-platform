<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Specification extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'server',
        'schema',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'schema' => 'array',
    ];
}
