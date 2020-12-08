<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Exports\TestCaseExport;
use App\Http\Resources\{ComponentResource, TestCaseResource, UseCaseResource};
use App\Imports\TestCaseImport;
use App\Models\{Component, TestCase, UseCase};
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TestCaseInfoController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('test-case.latest')->only(['show', 'edit']);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function show(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/info/show', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['useCase', 'components'])
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
        $this->authorize('update', $testCase);
        $testCaseResource = (new TestCaseResource(
            $testCase->load(['groups', 'useCase', 'testSteps', 'components'])
        ))->resolve();

        if (!$testCase->draft) {
            try {
                $rows = array_merge(
                    (new TestCaseExport())->getArray($testCase),
                    [
                        'test_case_group_id' => $testCase->test_case_group_id,
                        'public' => $testCase->public,
                        'draft' => true,
                    ]
                );
                $draftTestCase = (new TestCaseImport())->import($rows);
                $draftTestCase
                    ->groups()
                    ->sync($testCase->groups()->pluck('id'));
                $draftTestCase
                    ->owner()
                    ->associate(auth()->user())
                    ->save();

                return redirect()
                    ->route('admin.test-cases.info.edit', $draftTestCase->id)
                    ->with(
                        'success',
                        __('New draft test case created successfully')
                    );
            } catch (\Throwable $e) {
                $errorMessage = implode(
                    '<br>',
                    array_merge(
                        [$e->getMessage()],
                        !empty($e->validator)
                            ? $e->validator->errors()->all()
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
                'required',
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
}
