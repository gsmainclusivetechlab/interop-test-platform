<?php

namespace App\Imports;

use App\Models\QuestionnaireSection;
use App\Models\QuestionnaireTestCase;
use App\Models\TestCase;
use DB;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionnaireImport
{
    /**
     * @param array $rows
     */
    public function import(array $rows)
    {
        DB::transaction(function () use ($rows) {
            QuestionnaireSection::query()->delete();
            QuestionnaireTestCase::query()->delete();

            foreach (Arr::get($rows, 'questions', []) as $sectionRow) {
                /** @var QuestionnaireSection $section */
                $section = QuestionnaireSection::query()->create(
                    Arr::only(
                        $sectionRow,
                        QuestionnaireSection::make()->getFillable()
                    )
                );

                foreach (Arr::get($sectionRow, 'questions', []) as $question) {
                    $section->questions()->create([
                        'name' => Arr::get($question, 'property'),
                        'question' => Arr::get($question, 'question'),
                        'preconditions' => Arr::get($question, 'preconditions'),
                        'type' => Arr::get($question, 'type', 'select'),
                        'values' => Arr::get($question, 'values')
                    ]);
                }
            }

            foreach (Arr::get($rows, 'test_cases', []) as $testCaseName => $matches) {
                TestCase::query()
                    ->where(['slug' => $testCaseName])
                    ->existsOr(function () use ($testCaseName) {
                        throw new NotFoundHttpException(__('Test case with slug ":testcase" not found', [
                            'testcase' => $testCaseName
                        ]));
                    });

                QuestionnaireTestCase::query()->create([
                    'test_case_slug' => $testCaseName,
                    'matches' => $matches
                ]);
            }
        });
    }
}
