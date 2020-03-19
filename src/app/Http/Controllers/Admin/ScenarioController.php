<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Scenario;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $scenarios = Scenario::when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->withCount(['components', 'useCases', 'testCases'])
            ->latest()
            ->paginate();

        return view('admin.scenarios.index', compact('scenarios'));
    }

    /**
     * @param Scenario $scenario
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Scenario $scenario)
    {
        return view('admin.scenarios.show', compact('scenario'));
    }
}
