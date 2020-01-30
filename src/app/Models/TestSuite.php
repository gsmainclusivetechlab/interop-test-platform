<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSuite extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cases()
    {
        return $this->hasMany(TestCase::class);
    }
}
