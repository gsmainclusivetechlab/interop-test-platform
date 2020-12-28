<?php

namespace App\Models;

use Artisan;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Storage;

/**
 * Class Certificate
 *
 * @package App\Models
 *
 * @property int $id
 * @property int|null $group_id
 * @property string $name
 * @property string $ca_md5
 * @property string $ca_crt_path
 * @property string $client_crt_path
 * @property string $client_key_path
 * @property string $passphrase
 * @property Carbon $created_at
 *
 * @mixin Eloquent
 */
class Certificate extends Model
{
    const UPDATED_AT = null;

    /** @var string[] */
    protected $fillable = ['group_id', 'name', 'ca_crt_path', 'client_crt_path', 'client_key_path', 'passphrase'];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected static function booted(): void
    {
        static::saving(function (self $certificate) {
            $certificate->ca_md5 = md5_file(Storage::path($certificate->ca_crt_path));
        });

        static::saved(function (self $certificate) {
            if (static::where('ca_md5', $certificate->ca_md5)->count() == 1) {
                Artisan::queue('certificates:generate-ca');
            }
        });

        static::deleted(function (self $certificate) {
            Storage::delete([
                $certificate->ca_crt_path,
                $certificate->client_crt_path,
                $certificate->client_key_path
            ]);

            if (!static::where('ca_md5', $certificate->ca_md5)->exists()) {
                Artisan::queue('certificates:generate-ca');
            }
        });
    }

    public static function storeFile(Request $request, $key): string
    {
        return $request->file($key)->store('certificates');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
