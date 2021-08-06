<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faq
 *
 * @package App\Models
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property string|null $description
 * @property array $content
 * @property bool $active
 */
class Faq extends Model
{
    /** @var array */
    protected $fillable = [
        'description',
        'content',
        'active',
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $casts = [
        'content' => 'array',
    ];

    /** @var array */
    protected $attributes = [
        'active' => false,
    ];

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}
