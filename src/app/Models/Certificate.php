<?php

namespace App\Models;

use Artisan;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
 * @property string $certificable_type
 * @property int $certificable_id
 * @property Carbon $created_at
 *
 * @mixin Eloquent
 */
class Certificate extends Model
{
    const UPDATED_AT = null;

    /** @var string[] */
    protected $fillable = [
        'group_id',
        'name',
        'ca_crt_path',
        'client_crt_path',
        'client_key_path',
        'passphrase',
    ];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $certificate) {
            $certificate->ca_md5 = md5_file(
                Storage::path($certificate->ca_crt_path)
            );
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
                $certificate->client_key_path,
            ]);

            if (!static::where('ca_md5', $certificate->ca_md5)->exists()) {
                Artisan::queue('certificates:generate-ca');
            }
        });
    }

    public static function storeFile(
        Request $request,
        $key,
        $default = null
    ): ?string {
        if ($request->hasFile($key)) {
            $filePath = $request->file($key)->store('certificates');

            if ($filePath && $default) {
                Storage::delete($default);
            }
        }

        return $filePath ?? $default;
    }

    public function certificable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(
            Session::class,
            'session_components',
            'certificate_id',
            'session_id'
        );
    }

    public static function hasGroupCertificates(): bool
    {
        return static::groupCertificatesQuery()->exists();
    }

    public static function getGroupCertificates(): Collection
    {
        return static::groupCertificatesQuery()->get();
    }

    protected static function groupCertificatesQuery()
    {
        return static::whereHasMorph('certificable', Group::class, function (
            Builder $query
        ) {
            $query->whereHas('users', function (Builder $query) {
                $query->whereKey(
                    auth()
                        ->user()
                        ->getAuthIdentifier()
                );
            });
        });
    }

    public static function getCertificateAttribures(
        Request $request,
        ?self $certificate,
        string $caCrtKey = 'ca_crt',
        string $clientCrtKey = 'client_crt',
        string $clientKeyKey = 'client_key',
        string $passphrase = null
    ): array {
        return [
            'passphrase' => $passphrase ?? $request->get('passphrase'),
            'ca_crt_path' => static::storeFile(
                $request,
                $caCrtKey,
                $certificate->ca_crt_path ?? null
            ),
            'client_crt_path' => static::storeFile(
                $request,
                $clientCrtKey,
                $certificate->client_crt_path ?? null
            ),
            'client_key_path' => static::storeFile(
                $request,
                $clientKeyKey,
                $certificate->client_key_path ?? null
            ),
        ];
    }
}
