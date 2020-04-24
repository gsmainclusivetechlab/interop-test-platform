<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ComponentResource;
use App\Http\Resources\ScenarioResource;
use App\Models\Component;
use App\Http\Controllers\Controller;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class ComponentController extends Controller
{
    /**
     * ComponentController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Component::class, 'component');
    }

    /**
     * @param Scenario $scenario
     * @return \Inertia\Response
     */
    public function index(Scenario $scenario)
    {
        return Inertia::render('admin/components/index', [
            'scenario' => (new ScenarioResource($scenario))->resolve(),
            'components' => ComponentResource::collection(
                $scenario->components()
                    ->when(request('q'), function (Builder $query, $q) {
                        $query->where('name', 'like', "%{$q}%");
                    })
                    ->with(['apiService'])
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }
}
