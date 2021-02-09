<?php

namespace App\Http\Controllers\Sessions\Register\Traits;

use App\Models\{QuestionnaireSection, Session};

trait QuestionnaireKeys
{
    protected function getQuestionnaireKeys()
    {
        if (
            Session::isCompliance(session('session.type')) ||
            session('session.withQuestions')
        ) {
            return ',' .
                QuestionnaireSection::query()
                    ->pluck('id')
                    ->map(function ($section) {
                        return "session.questionnaire.{$section}";
                    })
                    ->implode(',');
        }

        return '';
    }
}
