<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\{ScenarioResource, UseCaseResource};
use App\Models\{Scenario, UseCase};
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScenarioController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Scenario::class, 'scenario', [
            'except' => ['show'],
        ]);
    }

    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('admin/scenarios/index', [
            'scenarios' => ScenarioResource::collection(
                Scenario::when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                    ->with(['testCases'])
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Scenario::class);
        return Inertia::render('admin/scenarios/create', [
            'useCases' => UseCaseResource::collection(
                UseCase::get()
            )->resolve(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'use_case_id' => ['required', 'integer', 'exists:use_cases,id'],
        ]);
        Scenario::create($request->input());

        return redirect()
            ->route('admin.scenarios.index')
            ->with('success', __('Scenario created successfully'));
    }

    /**
     * @param Scenario $scenario
     * @return Response
     */
    public function edit(Scenario $scenario)
    {
        return Inertia::render('admin/scenarios/edit', [
            'scenario' => (new ScenarioResource($scenario))->resolve(),
        ]);
    }

    /**
     * @param Scenario $scenario
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Scenario $scenario, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
        ]);
        $scenario->update($request->input());

        return redirect()
            ->route('admin.scenarios.index')
            ->with('success', __('Scenario updated successfully'));
    }

    /**
     * @param Scenario $scenario
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Scenario $scenario)
    {
        $scenario->delete();

        return redirect()
            ->back()
            ->with('success', __('Scenario deleted successfully'));
    }
}
