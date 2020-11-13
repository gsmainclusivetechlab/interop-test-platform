<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestionnaireTestCases
 *
 * @package App\Models
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $test_case_slug
 * @property array $matches
 */
class QuestionnaireTestCase extends Model
{
    /** @var string[] */
    protected $fillable = ['test_case_slug', 'matches'];

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $casts = [
        'matches' => 'array',
    ];
}
