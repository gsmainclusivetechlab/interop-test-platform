<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UseCaseResource;
use App\Models\UseCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
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
//            'except' => ['index'],
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
}
