<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Exports\TestCaseExport;
use App\Http\Resources\{
    ComponentResource,
    TestCaseResource,
    UseCaseResource,
};
use App\Imports\TestCaseImport;
use App\Models\{
    Component,
    TestCase,
    UseCase
};
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Yaml\Yaml;

class TestCaseInfoController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function show(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/info/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load([
                    'useCase',
                    'components'
                ])
            ))->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function edit(TestCase $testCase)
    {
        if (!$testCase->isLast()) {
            return $this->redirectToLast($testCase->last_version->id);
        }
        $this->authorize('update', $testCase);
        $testCaseResource = (new TestCaseResource(
            $testCase->load([
                'groups',
                'useCase',
                'testSteps',
                'components'
            ])
        ))->resolve();

        if (!$testCase->draft) {
            try {
                $rows = array_merge(Yaml::parse((new TestCaseExport())->export($testCase)), [
                    'test_case_group_id' => $testCase->test_case_group_id,
                    'public' => $testCase->public,
                    'draft' => true,
                ]);
                $draftTestCase = (new TestCaseImport())->import($rows);
                $draftTestCase->groups()->sync($testCase->groups()->pluck('id'));
//                $draftTestCase = $testCase->replicate(['uuid']);
//                $draftTestCase->draft = 1;
//                $draftTestCase->push();
//                $draftTestCase->groups()->sync($testCase->groups()->pluck('id'));
//                $draftTestCase->components()->sync($testCase->components()->pluck('id'));
//                foreach ($testCase->testSteps()->get() as $testStep) {
//                    /** @var TestStep $testStep */
//                    $draftTestStep = $draftTestCase->testSteps()->make($testStep->getAttributes());
//                    $draftTestStep->saveOrFail();
//                }
//                $draftTestCase->components()->sync($testCase->components()->pluck('id'));

                $draftTestCase
                    ->owner()
                    ->associate(auth()->user())
                    ->save();

                return redirect()
                    ->route('admin.test-cases.info.edit', $draftTestCase->id)
                    ->with('success', __('New draft test case created successfully'));
            } catch (\Throwable $e) {
                $errorMessage = implode(
                    '<br>',
                    array_merge(
                        [$e->getMessage()],
                        !empty($e->validator)
                            ? $e->validator
                            ->errors()
                            ->all()
                            : []
                    )
                );
                return redirect()
                    ->back()
                    ->with('error', $errorMessage);
            }
        }

        return Inertia::render('admin/test-cases/info/edit', [
            'testCase' => $testCaseResource,
            'components' => ComponentResource::collection(
                Component::get()
            )->resolve(),
            'useCases' => UseCaseResource::collection(
                UseCase::get()
            )->resolve(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(TestCase $testCase, Request $request)
    {
        $this->authorize('update', $testCase);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                Rule::unique('test_cases')->ignore(
                    $testCase->test_case_group_id,
                    'test_case_group_id'
                ),
            ],
            'description' => ['string', 'nullable'],
            'precondition' => ['string', 'nullable'],
            'behavior' => ['required', 'string', 'max:255'],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
            'groups_id.*' => ['integer', 'exists:groups,id'],
            'components_id.*' => ['integer', 'exists:components,id'],
        ]);
        $testCase->update($request->input());
        $testCase->components()->sync($request->input('components_id'));
        $testCase->groups()->sync($request->input('groups_id'));

        return redirect()
            ->route('admin.test-cases.info.show', $testCase->id)
            ->with('success', __('Test case updated successfully'));
    }

    /**
     * @param string|array $params
     * @return RedirectResponse
     */
    protected function redirectToLast($params)
    {
        return redirect()->route(\Route::currentRouteName(), $params);
    }
}
