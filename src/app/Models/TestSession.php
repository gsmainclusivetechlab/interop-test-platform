<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
