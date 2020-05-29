<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UseCaseResource;
use App\Models\UseCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UseCaseController extends Controller
{
    /**
     * UseCaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(UseCase::class, 'use_case', [
            'except' => ['show'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/use-cases/index', [
            'useCases' => UseCaseResource::collection(
                UseCase::when(request('q'), function (Builder $query, $q) {
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
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('admin/use-cases/create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
        ]);
        UseCase::create($request->input());

        return redirect()
            ->route('admin.use-cases.index')
            ->with('success', __('Use case created successfully'));
    }

    /**
     * @param UseCase $useCase
     * @return \Inertia\Response
     */
    public function edit(UseCase $useCase)
    {
        return Inertia::render('admin/use-cases/edit', [
            'useCase' => (new UseCaseResource($useCase))->resolve(),
        ]);
    }

    /**
     * @param UseCase $useCase
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UseCase $useCase, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
        ]);
        $useCase->update($request->input());

        return redirect()
            ->route('admin.use-cases.index')
            ->with('success', __('Use case updated successfully'));
    }

    /**
     * @param UseCase $useCase
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(UseCase $useCase)
    {
        $useCase->delete();

        return redirect()
            ->back()
            ->with('success', __('Use case deleted successfully'));
    }
}
