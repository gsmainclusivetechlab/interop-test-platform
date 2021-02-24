<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Storage;

/**
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $file_name
 * @property string $file_path
 *
 * @property Session|GroupEnvironment $environmentable
 */
class FileEnvironment extends Model
{
    /** @var string[] */
    protected $fillable = ['name', 'file_name', 'file_path'];

    /** @var bool */
    public $timestamps = false;

    protected static function booted(): void
    {
        static::deleted(function (self $file) {
            if (!static::where('file_path', $file->file_path)->count()) {
                Storage::delete($file->file_path);
            }
        });
    }

    /**
     * @param Session|GroupEnvironment|Model $model
     * @param array $formData
     */
    public static function syncEnvironments($model, $formData)
    {
        $environments = $model->fileEnvironments;

        collect($formData)->each(function ($file, $name) use (
            $model,
            &$environments
        ) {
            if ($file instanceof UploadedFile) {
                $model->fileEnvironments()->create([
                    'name' => $name,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $file->store('environments'),
                ]);
            } elseif ($file) {
                $environment = static::find($file);
                if (
                    class_basename($model) !=
                    class_basename($environment->environmentable)
                ) {
                    $model->fileEnvironments()->create([
                        'name' => $name,
                        'file_name' => $environment->file_name,
                        'file_path' => $environment->file_path,
                    ]);
                } else {
                    $environment->update(['name' => $name]);

                    $environments = $environments->where('id', '!=', $file);
                }
            }
        });

        $environments->each(function ($environment) {
            $environment->delete();
        });
    }

    public function environmentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPathAttribute(): string
    {
        return Storage::path($this->file_path);
    }
}
