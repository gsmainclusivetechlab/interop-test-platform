<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Component;
use App\Http\Controllers\Controller;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Scenario $scenario)
    {
        $components =$scenario->components()
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('admin.components.index', compact('scenario', 'components'));
    }

    /**
     * @param Component $component
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Component $component)
    {
        return view('admin.components.show', compact('component'));
    }
}
