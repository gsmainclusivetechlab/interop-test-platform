<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class TestRequest extends Model
{
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'test_requests';

    /**
     * @var array
     */
    protected $fillable = [
        'method',
        'uri',
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
