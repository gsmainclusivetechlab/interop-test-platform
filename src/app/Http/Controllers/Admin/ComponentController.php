<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ComponentResource;
use App\Models\Component;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ComponentController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Component::class, 'component', [
            'except' => ['show'],
        ]);
    }

    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('admin/components/index', [
            'components' => ComponentResource::collection(
                Component::when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                    ->with(['connections'])
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return Response
     */
    public function create()
    {
        return Inertia::render('admin/components/create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'base_url' => ['required', 'url', 'max:255'],
            'description' => ['string', 'nullable'],
            'sutable' => ['required', 'boolean'],
            'connections_id.*' => ['integer', 'exists:components,id'],
        ]);
        DB::transaction(function () use ($request) {
            $component = Component::create($request->input());
            $component->connections()->sync($request->input('connections_id'));
        });

        return redirect()
            ->route('admin.components.index')
            ->with('success', __('Component created successfully'));
    }

    /**
     * @param Component $component
     * @return Response
     */
    public function edit(Component $component)
    {
        return Inertia::render('admin/components/edit', [
            'component' => (new ComponentResource(
                $component->load(['connections'])
            ))->resolve(),
        ]);
    }

    /**
     * @param Component $component
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Component $component, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'base_url' => ['required', 'url', 'max:255'],
            'description' => ['string', 'nullable'],
            'sutable' => ['required', 'boolean'],
            'connections_id.*' => ['integer', 'exists:components,id'],
        ]);
        DB::transaction(function () use ($component, $request) {
            $component->update($request->input());
            $component->connections()->sync($request->input('connections_id'));
        });

        return redirect()
            ->route('admin.components.index')
            ->with('success', __('Component updated successfully'));
    }

    /**
     * @param Component $component
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Component $component)
    {
        $component->delete();

        return redirect()
            ->back()
            ->with('success', __('Component deleted successfully'));
    }

    /**
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function connectionCandidates()
    {
        $this->authorize('viewAny', Component::class);

        return ComponentResource::collection(
            Component::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->when(request('component_id'), function (
                    Builder $query,
                    $componentId
                ) {
                    $query->whereKeyNot($componentId);
                })
                ->paginate()
        );
    }
}
