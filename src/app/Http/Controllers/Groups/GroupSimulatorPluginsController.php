<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimulatorPluginRequest;
use App\Models\SimulatorPlugin;
use Exception;
use App\Http\Resources\{GroupResource, SimulatorPluginResource};
use App\Models\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\{Inertia, Response};
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GroupSimulatorPluginsController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!env('FEATURE_SIMULATOR_PLUGIN')) {
                abort(404);
            }

            return $next($request);
        });
    }

    /**
     * @param Group $group
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Group $group): Response
    {
        $this->authorize('admin', $group);

        return Inertia::render('groups/simulator-plugins/index', [
            'group' => GroupResource::make($group)->resolve(),
            'plugins' => SimulatorPluginResource::collection(
                $group
                    ->simulatorPlugins()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Group $group
     * @return Response
     * @throws AuthorizationException
     */
    public function create(Group $group): Response
    {
        $this->authorize('admin', $group);

        return Inertia::render('groups/simulator-plugins/create', [
            'group' => GroupResource::make($group)->resolve(),
        ]);
    }

    /**
     * @param Group $group
     * @param SimulatorPluginRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(
        Group $group,
        SimulatorPluginRequest $request
    ): RedirectResponse {
        $this->authorize('admin', $group);

        $group->simulatorPlugins()->create(
            $request->validated() + [
                'file_path' => $request
                    ->file('file')
                    ->store('simulator-plugins'),
            ]
        );

        return redirect()
            ->route('groups.plugins.index', $group)
            ->with('success', __('Simulator plugin uploaded successfully'));
    }

    /**
     * @param SimulatorPlugin $plugin
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(SimulatorPlugin $plugin): Response
    {
        $this->authorize('update', $plugin);

        return Inertia::render('groups/simulator-plugins/edit', [
            'group' => GroupResource::make($plugin->group)->resolve(),
            'plugin' => SimulatorPluginResource::make($plugin)->resolve(),
        ]);
    }

    /**
     * @param SimulatorPlugin $plugin
     * @param SimulatorPluginRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(
        SimulatorPlugin $plugin,
        SimulatorPluginRequest $request
    ): RedirectResponse {
        $this->authorize('update', $plugin);

        $plugin->update($request->validated());

        if ($file = $request->file('file')) {
            $plugin->update([
                'file_path' => $file->store('simulator-plugins'),
            ]);
        }

        return redirect()
            ->route('groups.plugins.index', $plugin->group)
            ->with('success', __('Simulator plugin updated successfully'));
    }

    /**
     * @param SimulatorPlugin $plugin
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(SimulatorPlugin $plugin): RedirectResponse
    {
        $this->authorize('delete', $plugin);

        $plugin->delete();

        return redirect()
            ->route('groups.plugins.index', $plugin->group)
            ->with('success', __('Simulator plugin deleted successfully'));
    }

    /**
     * @param SimulatorPlugin $plugin
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function download(SimulatorPlugin $plugin): BinaryFileResponse
    {
        $this->authorize('download', $plugin);

        return response()->download(
            Storage::path($plugin->file_path),
            str_replace('/', '', substr($plugin->name, 0, 50) . '.js')
        );
    }
}
