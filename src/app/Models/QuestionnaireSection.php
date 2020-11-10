<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class QuestionnaireSection
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property QuestionnaireQuestions[]|Collection $questions
 */
class QuestionnaireSection extends Model
{
    /** @var string[] */
    protected $fillable = ['name', 'description'];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany(QuestionnaireQuestions::class, 'section_id');
    }
}
