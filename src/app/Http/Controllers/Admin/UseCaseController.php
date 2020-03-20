<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Scenario;
use App\Models\UseCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Scenario $scenario)
    {
        $useCases = $scenario->useCases()
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->withCount('testCases')
            ->latest()
            ->paginate();

        return view('admin.use-cases.index', compact('scenario', 'useCases'));
    }
}
