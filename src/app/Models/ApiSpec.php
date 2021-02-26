<?php declare(strict_types=1);

namespace App\Models;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * @mixin Eloquent
 *
 * @property string $name
 * @property string $api_path
 * @property string $file_path
 *
 * @property-read OpenApi $openapi
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
    protected $fillable = ['name', 'description', 'file_path', 'api_path'];

    protected static function booted(): void
    {
        static::deleted(function (self $apiSpec) {
            Storage::delete([$apiSpec->api_path, $apiSpec->file_path]);
        });
    }

    public function getOpenapiAttribute()
    {
        return Reader::readFromJsonFile(Storage::path($this->api_path));
    }
}
