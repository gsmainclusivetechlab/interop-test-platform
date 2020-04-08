<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TestDataExample
 *
 * @package App\Models
 */
class TestDataExample extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uri',
        'method',
        'request',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];
}
