<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
