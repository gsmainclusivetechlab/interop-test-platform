<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ScenarioResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Scenario;
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
        $this->authorizeResource(UseCase::class, 'use_case');
    }

    /**
     * @param Scenario $scenario
     * @return \Inertia\Response
     */
    public function index(Scenario $scenario)
    {
        return Inertia::render('admin/use-cases/index', [
            'scenario' => (new ScenarioResource($scenario))->resolve(),
            'useCases' => UseCaseResource::collection(
                $scenario->useCases()
                    ->when(request('q'), function (Builder $query, $q) {
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
