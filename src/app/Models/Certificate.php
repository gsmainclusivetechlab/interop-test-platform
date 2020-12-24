<?php

namespace App\Models;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 *
 * @mixin \Eloquent
 */
class Certificate extends Model
{
    const UPDATED_AT = null;

    /** @var string[] */
    protected $fillable = ['group_id', 'name', 'ca_crt_path', 'client_crt_path', 'client_key_path'];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected static function booted(): void
    {
        static::saving(function (self $certificate) {
            $certificate->ca_md5 = md5_file(Storage::path($certificate->ca_crt_path));
        });

        static::deleted(function (self $certificate) {
            Storage::delete([
                $certificate->ca_crt_path,
                $certificate->client_crt_path,
                $certificate->client_key_path
            ]);
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
