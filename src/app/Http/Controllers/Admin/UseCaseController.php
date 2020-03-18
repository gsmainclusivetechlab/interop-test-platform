<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Imports\TestCasesImport;
use App\Models\Scenario;
use App\Models\UseCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Yaml\Yaml;

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
            ->latest()
            ->paginate();

        return view('admin.use-cases.index', compact('scenario', 'useCases'));
    }

    /**
     * @param UseCase $useCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(UseCase $useCase)
    {
        $import = new TestCasesImport($useCase);
        $import->import(Yaml::parseFile(database_path('seeds/data/authorized-transaction.yaml')));

        dd(1);

        return view('admin.use-cases.show', compact('useCase'));
    }
}
