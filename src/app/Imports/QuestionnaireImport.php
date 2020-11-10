<?php

namespace App\Imports;

use App\Models\QuestionnaireSection;
use App\Models\QuestionnaireTestCase;
use App\Models\TestCase;
use DB;
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

            foreach ($rows['questions'] as $sectionRow) {
                /** @var QuestionnaireSection $section */
                $section = QuestionnaireSection::query()->create([
                    'name' => $sectionRow['name'],
                    'description' => $sectionRow['description']
                ]);

                foreach ($sectionRow['questions'] as $question) {
                    $section->questions()->create([
                        'name' => $question['property'],
                        'question' => $question['question'],
                        'preconditions' => $question['preconditions'] ?? null,
                        'type' => $question['type'] ?? 'select',
                        'values' => $question['values']
                    ]);
                }
            }

            foreach ($rows['test_cases'] as $testCaseName => $matches) {
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
