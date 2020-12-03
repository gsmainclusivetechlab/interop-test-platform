<?php

namespace App\Models;

use Arr;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class QuestionnaireQuestions
 *
 * @package App\Models
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property int $section_id
 * @property string $name
 * @property string $question
 * @property array|null $preconditions
 * @property string $type
 * @property array $values
 *
 * @property QuestionnaireAnswer[]|Collection $answers
 */
class QuestionnaireQuestions extends Model
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = [
        'name',
        'question',
        'preconditions',
        'type',
        'values',
    ];

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $casts = [
        'preconditions' => 'array',
        'values' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'question_id');
    }

    /**
     * @return bool
     */
    public function isMultiSelect(): bool
    {
        return $this->type == 'multiselect';
    }

    /**
     * @return array
     */
    public function getAnswersNamesAttribute()
    {
        return $this->answers
            ->map(function (QuestionnaireAnswer $answer) {
                return Arr::get($this->values, $answer->answer);
            })
            ->all();
    }
}
