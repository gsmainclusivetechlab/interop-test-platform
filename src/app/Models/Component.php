<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Component extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
