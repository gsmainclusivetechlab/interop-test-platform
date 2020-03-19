<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Imports\TestCaseImport;
use App\Models\Scenario;
use App\Models\TestCase;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Yaml\Yaml;

class TestCaseController extends Controller
{
    /**
     * TestCaseController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestCase::class, 'test_case');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Scenario $scenario)
    {
        $import = new TestCaseImport($scenario);
        $import->import(Yaml::parseFile(database_path('seeds/data/authorized-transaction.yaml')));

        dd(1);

        $testCases = $scenario->testCases()
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('admin.test-cases.index', compact('scenario', 'testCases'));
    }

    /**
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TestCase $testCase)
    {
        return view('admin.test-cases.show', compact('testCase'));
    }
}
