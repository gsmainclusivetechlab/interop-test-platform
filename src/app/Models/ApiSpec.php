<?php declare(strict_types=1);

namespace App\Models;

use App\Casts\OpenApiCast;
use cebe\openapi\spec\OpenApi;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * @mixin Eloquent
 *
 * @property string $name
 * @property OpenApi $openapi
 */
class ApiSpec extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_specs';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'openapi',
        'description',
        'file_path'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'openapi' => OpenApiCast::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (self $apiSpec) {
            Storage::delete($apiSpec->file_path);
        });
    }
}
