<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestPlatform extends Model
{
    /**
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scenario()
    {
        return $this->belongsTo(TestScenario::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specificationVersion()
    {
        return $this->belongsTo(SpecificationVersion::class);
    }
}
