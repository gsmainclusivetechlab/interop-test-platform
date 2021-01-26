<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestionnaireAnswer
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $session_id
 * @property int $question_id
 * @property string $answer
 */
class QuestionnaireAnswer extends Model
{
    /** @var string[] */
    protected $fillable = ['question_id', 'answer'];

    /** @var bool */
    public $timestamps = false;
}
