<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];
}
