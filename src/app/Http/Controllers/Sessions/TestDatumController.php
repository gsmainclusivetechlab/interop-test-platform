<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreTestDatumRequest;
use App\Http\Requests\Sessions\UpdateTestDatumRequest;
use App\Jobs\RunTestDatumJob;
use App\Models\TestCase;
use App\Models\Session;
use App\Models\TestDatum;
use Illuminate\Database\Eloquent\Builder;

class TestDatumController extends Controller
{
    /**
     * TestDatumController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Session $session, TestCase $testCase)
    {
        $testCase = $session->testCases()
            ->where('test_case_id', $testCase->id)
            ->firstOrFail();
        $testData = $session->testData()
            ->where('test_case_id', $testCase->id)
            ->when(request('q'), function (Builder $query, $q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate();

        return view('sessions.test-data.index', compact('session', 'testCase', 'testData'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Session $session, TestCase $testCase)
    {
        return view('sessions.test-data.create', compact('session', 'testCase'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param StoreTestDatumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Session $session, TestCase $testCase, StoreTestDatumRequest $request)
    {
        dd($request->input());
        $session->testData()->create(array_merge([
            'test_case_id' => $testCase->id,
        ], $request->input()));

        return redirect()
            ->route('sessions.test-cases.test-data.index', [$session, $testCase])
            ->with('success', __('Test data created successfully'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param TestDatum $testDatum
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Session $session, TestCase $testCase, TestDatum $testDatum)
    {
        return view('sessions.test-data.edit', compact('session', 'testCase', 'testDatum'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param TestDatum $testDatum
     * @param UpdateTestDatumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Session $session, TestCase $testCase, TestDatum $testDatum, UpdateTestDatumRequest $request)
    {
        $session->testData()->create($request->input());

        return redirect()
            ->route('sessions.test-cases.test-data.index', [$session, $testCase])
            ->with('success', __('Test data updated successfully'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param TestDatum $testDatum
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Session $session, TestCase $testCase, TestDatum $testDatum)
    {
        $testDatum->delete();

        return redirect()
            ->back()
            ->with('success', __('Test data deleted successfully'));
    }

    /**
     * @param Session $session
     * @param TestCase $testCase
     * @param TestDatum $testDatum
     * @return \Illuminate\Http\RedirectResponse
     */
    public function run(Session $session, TestCase $testCase, TestDatum $testDatum)
    {
        RunTestDatumJob::dispatch($testDatum);

        return redirect()
            ->back()
            ->with('success', __('Test data started run successfully'));
    }
}
