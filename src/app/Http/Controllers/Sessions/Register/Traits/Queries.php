<?php

namespace App\Http\Controllers\Sessions\Register\Traits;

use App\Models\Component;
use App\Models\QuestionnaireTestCase;
use App\Models\Session;
use App\Models\TestCase;
use App\Models\UseCase;
use Arr;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Validator;

trait Queries
{
    /**
     * @return Builder[]|Collection
     */
    protected function getUseCases()
    {
        return UseCase::with([
            'testCases' => function ($query) {
                $this->getTestCasesQuery($query);
            },
        ])
            ->whereHas('testCases', function ($query) {
                $this->getTestCasesQuery($query);
            })
            ->get();
    }

    /**
     * @param Builder $query
     *
     * @return BuildsQueries|Builder|mixed
     */
    public function getTestCasesQuery($query)
    {
        $testCases = $this->getTestCases();

        return $query
            ->where(function ($query) {
                $query->available();
            })
            ->when(
                !auth()
                    ->user()
                    ->can('viewAny', TestCase::class),
                function ($query) {
                    $query->where('public', true);
                }
            )
            ->when($testCases !== null, function (Builder $query) use (
                $testCases
            ) {
                $query->whereIn('slug', $testCases ?: ['']);
            })
            ->lastPerGroup(false);
    }

    /**
     * @return Component[]|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getComponents()
    {
        $testComponentsQuery = function ($query) {
            $query->whereHas('testCases', function (Builder $query) {
                $query->whereIn('id', session('session.info.test_cases', [0]));
            });
        };

        return Component::where($testComponentsQuery)
            ->with([
                'testCases',
                'connections' => function ($query) use ($testComponentsQuery) {
                    $query->with('testCases')->where($testComponentsQuery);
                },
            ])
            ->get();
    }

    /**
     * @param Collection $components
     *
     * @return mixed
     */
    protected function getVersions($components)
    {
        return $components->mapWithKeys(function (Component $component) {
            $versions = $component->testCases
                ->whereIn('id', session('session.info.test_cases'))
                ->pluck('pivot.component_versions', 'id')
                ->filter();

            if (!$versions->count()) {
                return [];
            }

            if ($versions->count() > 1) {
                $availableVersions = collect(
                    array_intersect(...$versions->all())
                )->values();

                if (!$availableVersions->count()) {
                    return [
                        $component->id => __(
                            '<b>Test cases that you selected are incompatible.</b><br>:testCases',
                            [
                                'testCases' => $versions
                                    ->map(function (
                                        $versions,
                                        $testCaseId
                                    ) use ($component) {
                                        return sprintf(
                                            '"%s" requires version(s) "%s" of "%s"',
                                            $component->testCases->firstWhere(
                                                'id',
                                                $testCaseId
                                            )->name,
                                            implode(', ', $versions),
                                            $component->name
                                        );
                                    })
                                    ->implode('<br>'),
                            ]
                        ),
                    ];
                }
            } else {
                $availableVersions = $versions->first();
            }

            return [$component->id => $availableVersions];
        });
    }

    /**
     * @param bool $withQuestions
     *
     * @return array|null
     */
    protected function getTestCases(bool $withQuestions = false)
    {
        if (Session::isCompliance(session('session.type')) || $withQuestions) {
            $answers = Arr::collapse(session('session.questionnaire'));

            $testCases = [];
            QuestionnaireTestCase::query()->each(function (
                QuestionnaireTestCase $questionnaireTestCase
            ) use ($answers, &$testCases) {
                if ($this->includeTestCase($questionnaireTestCase, $answers)) {
                    $testCases[] = $questionnaireTestCase->test_case_slug;
                }
            });
        }

        return $testCases ?? null;
    }

    protected function includeTestCase(
        QuestionnaireTestCase $questionnaireTestCase,
        array $answers
    ): bool {
        foreach ($questionnaireTestCase->matches as $attribute => $match) {
            $hasAnswer = false;
            foreach ((array) Arr::get($answers, $attribute, []) as $answer) {
                $validator = Validator::make(
                    [$attribute => $answer],
                    [$attribute => $match]
                );

                if (!$validator->fails()) {
                    $hasAnswer = true;
                }
            }

            if (!$hasAnswer) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array|Collection $availableTestCasesIds
     *
     * @return mixed
     */
    protected function getTestCasesIds($availableTestCasesIds)
    {
        return TestCase::whereIn('slug', $this->getTestCases(true) ?: [''])
            ->whereIn('id', $availableTestCasesIds)
            ->available()
            ->lastPerGroup(false)
            ->pluck('id');
    }
}
