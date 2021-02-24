<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ApiSpecResource;
use App\Models\ApiSpec;
use App\Http\Controllers\Controller;
use cebe\openapi\Reader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class ApiSpecController extends Controller
{
    /**
     * ApiSpecController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(ApiSpec::class, 'api_spec', [
            'only' => ['index', 'destroy'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/api-specs/index', [
            'apiSpecs' => ApiSpecResource::collection(
                ApiSpec::when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function showImportForm()
    {
        $this->authorize('create', ApiSpec::class);
        return Inertia::render('admin/api-specs/import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $this->authorize('create', ApiSpec::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);
        $file = $request->file('file');

        try {
            ApiSpec::create([
                'name' => $request->input('name'),
                'openapi' => Reader::readFromYamlFile(
                    $file->path()
                ),
                'file_path' => $file->storeAs(
                    'openapis',
                    Str::random(32) . '.yaml'
                ),
            ]);

            return redirect()
                ->route('admin.api-specs.index')
                ->with('success', __('Api spec created successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param ApiSpec $apiSpec
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(ApiSpec $apiSpec)
    {
        $this->authorize('update', $apiSpec);
        return Inertia::render('admin/api-specs/edit', [
            'apiSpec' => (new ApiSpecResource($apiSpec))->resolve(),
        ]);
    }

    /**
     * @param ApiSpec $apiSpec
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateSpec(ApiSpec $apiSpec, Request $request)
    {
        $this->authorize('update', $apiSpec);
        $request->validate([
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);
        $file = $request->file('file');

        try {
            $path = $apiSpec->file_path;
            $apiSpec->update([
                'openapi' => Reader::readFromYamlFile(
                    $file->path()
                ),
                'file_path' => $file->storeAs(
                    'openapis',
                    Str::random(32) . '.yaml'
                ),
            ]);
            app()->terminating(function () use ($path) {
                \Storage::delete($path);
            });

            return redirect()
                ->route('admin.api-specs.index')
                ->with('success', __('Api spec updated successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param ApiSpec $apiSpec
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ApiSpec $apiSpec)
    {
        if ($apiSpec->delete()) {
            \Storage::delete($apiSpec->file_path);
        }

        return redirect()
            ->back()
            ->with('success', __('Api spec deleted successfully'));
    }

    /**
     * @param ApiSpec $apiSpec
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(ApiSpec $apiSpec)
    {
        return response()->download(
            \Storage::path($apiSpec->file_path),
            str_replace(
                '/',
                '',
                $apiSpec->name . '.yaml'
            )
        );
    }
}
