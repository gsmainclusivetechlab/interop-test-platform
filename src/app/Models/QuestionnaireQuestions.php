<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestionnaireQuestions
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $section_id
 * @property string $name
 * @property string $question
 * @property array|null $preconditions
 * @property string $type
 * @property array $values
 */
class QuestionnaireQuestions extends Model
{
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
     * @return bool
     */
    public function isMultiSelect(): bool
    {
        return $this->type == 'multiselect';
    }
}
