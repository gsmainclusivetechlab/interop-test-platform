<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestResponse extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_responses';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'headers',
        'body',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'headers' => 'array',
        'body' => 'array'
    ];
}
