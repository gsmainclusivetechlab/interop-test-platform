<?php

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComponentRequest;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\TestCaseResource;
use App\Models\Component;
use App\Models\TestCase;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ComponentsController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('test-case.latest')->except(['index']);
        $this->authorizeResource(Component::class, 'component');
    }

    public function index(TestCase $testCase): Response
    {
        $this->authorize('update', $testCase);

        return Inertia::render('admin/test-cases/components/index', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'components' => ComponentResource::collection(
                $testCase->components
            ),
        ]);
    }

    public function create(TestCase $testCase): Response
    {
        $this->authorize('update', $testCase);

        return Inertia::render('admin/test-cases/components/create', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
        ]);
    }

    public function store(
        TestCase $testCase,
        ComponentRequest $request
    ): RedirectResponse {
        $this->authorize('update', $testCase);

        $component = Component::firstOrCreate(
            $data = $request->only('slug'),
            $data
        );

        $testCase->components()->attach($component, [
            'component_name' => $request->get('name'),
            'component_versions' => $request->get('versions'),
        ]);

        return redirect()
            ->route('admin.test-cases.components.index', $testCase->id)
            ->with('success', __('Component created successfully'));
    }

    public function edit(TestCase $testCase, Component $component): Response
    {
        $this->authorize('update', $testCase);

        return Inertia::render('admin/test-cases/components/edit', [
            'testCase' => (new TestCaseResource($testCase))->resolve(),
            'component' => (new ComponentResource(
                $testCase->components()->findOrFail($component->id)
            ))->resolve(),
        ]);
    }

    public function update(
        TestCase $testCase,
        Component $component,
        ComponentRequest $request
    ): RedirectResponse {
        $this->authorize('update', $testCase);

        $model = Component::firstOrCreate(
            $data = $request->only('slug'),
            $data
        );

        $pivotData = [
            'component_name' => $request->get('name'),
            'component_versions' => $request->get('versions'),
        ];

        if ($component->id != $model->id) {
            $testCase->components()->detach($component);

            $component->deleteWithoutTestCases();

            $testCase->components()->attach($model, $pivotData);
        } else {
            $testCase
                ->components()
                ->updateExistingPivot($component, $pivotData);
        }

        return redirect()
            ->route('admin.test-cases.components.index', $testCase->id)
            ->with('success', __('Component updated successfully'));
    }

    public function destroy(
        TestCase $testCase,
        Component $component
    ): RedirectResponse {
        $testCase->components()->detach($component);

        $component->deleteWithoutTestCases();

        return redirect()
            ->route('admin.test-cases.components.index', $testCase->id)
            ->with('success', __('Component deleted successfully'));
    }
}
