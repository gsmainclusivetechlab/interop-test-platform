<?php declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Specification extends Model
{
    /**
     * @var string
     */
    protected $table = 'specifications';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'schema',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'schema' => 'array',
    ];
}
