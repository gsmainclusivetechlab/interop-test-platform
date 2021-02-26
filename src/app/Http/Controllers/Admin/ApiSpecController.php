<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ApiSpecResource;
use App\Models\ApiSpec;
use App\Http\Controllers\Controller;
use cebe\openapi\exceptions\IOException;
use cebe\openapi\exceptions\TypeErrorException;
use cebe\openapi\exceptions\UnresolvableReferenceException;
use cebe\openapi\Reader;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use Inertia\Response;
use Storage;
use Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

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
     * @return Response
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
     * @return Response
     */
    public function showImportForm()
    {
        $this->authorize('create', ApiSpec::class);
        return Inertia::render('admin/api-specs/import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function import(Request $request)
    {
        $this->authorize('create', ApiSpec::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            ApiSpec::create(
                array_merge(
                    ['name' => $request->input('name')],
                    $this->storeApiSpec($request->file('file'))
                )
            );

            return redirect()
                ->route('admin.api-specs.index')
                ->with('success', __('Api spec created successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param ApiSpec $apiSpec
     * @return Response
     * @throws AuthorizationException
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
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updateSpec(ApiSpec $apiSpec, Request $request)
    {
        $this->authorize('update', $apiSpec);
        $request->validate([
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            $oldFiles = [$apiSpec->file_path, $apiSpec->api_path];
            $apiSpec->update($this->storeApiSpec($request->file('file')));
            app()->terminating(function () use ($oldFiles) {
                Storage::delete($oldFiles);
            });

            return redirect()
                ->route('admin.api-specs.index')
                ->with('success', __('Api spec updated successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param ApiSpec $apiSpec
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ApiSpec $apiSpec)
    {
        $apiSpec->delete();

        return redirect()
            ->back()
            ->with('success', __('Api spec deleted successfully'));
    }

    /**
     * @param ApiSpec $apiSpec
     * @return BinaryFileResponse
     */
    public function download(ApiSpec $apiSpec)
    {
        return response()->download(
            Storage::path($apiSpec->file_path),
            str_replace('/', '', $apiSpec->name . '.yaml')
        );
    }

    /**
     * @param $file
     * @return array
     * @throws IOException
     * @throws TypeErrorException
     * @throws UnresolvableReferenceException
     */
    protected function storeApiSpec(UploadedFile $file)
    {
        $path = 'openapis/' . Str::random(40) . '.txt';
        $spec = Reader::readFromYamlFile($file->path());
        $data = json_encode($spec->getSerializableData());

        return [
            'api_path' => Storage::put($path, $data),
            'file_path' => $file->store('openapis'),
        ];
    }
}
