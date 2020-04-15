<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ScenarioResource;
use App\Models\Scenario;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class ScenarioController extends Controller
{
    /**
     * ScenarioController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Scenario::class, 'scenario');
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/scenarios/index', [
            'scenarios' => ScenarioResource::collection(
                Scenario::when(request('q'), function (Builder $query, $q) {
                    $query->where('name', 'like', "%{$q}%");
                })
                    ->withCount(['components', 'useCases', 'testCases'])
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }

    /**
     * @param Scenario $scenario
     * @return \Inertia\Response
     */
    public function show(Scenario $scenario)
    {
        return Inertia::render('admin/scenarios/show', [
            'scenario' => new ScenarioResource($scenario),
        ]);
    }
}
