<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

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
 *
 * @mixin Eloquent
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

    /**
     * @param int $sectionId
     *
     * @return QuestionnaireSection|\Illuminate\Database\Eloquent\Builder|Model|Builder|object|null
     */
    public static function previousSection($sectionId)
    {
        return static::getSection('<', $sectionId, 'desc');
    }

    /**
     * @param int $sectionId
     *
     * @return QuestionnaireSection|\Illuminate\Database\Eloquent\Builder|Model|Builder|object|null
     */
    public static function nextSection($sectionId)
    {
        return static::getSection('>', $sectionId);
    }

    /**
     * @param string $operator
     * @param int $sectionId
     * @param string $orderDirection
     *
     * @return QuestionnaireSection|\Illuminate\Database\Eloquent\Builder|Model|Builder|object|null
     */
    protected static function getSection($operator, $sectionId, $orderDirection = 'asc')
    {
        return static::where('id', $operator, $sectionId)
            ->orderBy('id', $orderDirection)
            ->first();
    }
}
