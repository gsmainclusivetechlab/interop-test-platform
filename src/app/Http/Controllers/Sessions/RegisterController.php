<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreSessionSutsRequest;
use App\Http\Requests\Sessions\StoreSessionRequest;
use App\Http\Requests\Sessions\UpdateSessionRequest;
use App\Models\Session;
use App\Models\UseCase;
use App\Models\Scenario;
use Illuminate\Support\Arr;

class RegisterController extends Controller
{
    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $scenario = Scenario::firstOrFail();
        $useCases = $scenario->useCases()->whereHas('testCases')->get();

        return view('sessions.register.create', compact('scenario', 'useCases'));
    }

    /**
     * @param StoreSessionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSessionRequest $request)
    {
        $user = auth()->user();
        $scenario = Scenario::firstOrFail();
        $session = $user->sessions()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'scenario_id' => $scenario->id,
        ]);
        $session->testCases()->attach($request->input('test_cases'));

        return redirect()->route('sessions.register.config', $session);
    }

    /**
     * @param Session $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Session $session)
    {
        $scenario = $session->scenario;
        $useCases = $scenario->useCases()->whereHas('testCases')->get();

        return view('sessions.register.edit', compact('session', 'scenario', 'useCases'));
    }

    /**
     * @param Session $session
     * @param UpdateSessionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Session $session, UpdateSessionRequest $request)
    {
        $session->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        $session->testCases()->sync($request->input('test_cases'));

        return redirect()->route('sessions.register.config', $session);
    }

    /**
     * @param Session $session
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showConfig(Session $session)
    {
        $scenario = $session->scenario;
        $useCases = $scenario->useCases()->whereHas('testCases')->get();
        $components = $scenario->components()->whereHas('apiService')->get();

        return view('sessions.register.config', compact('session', 'scenario', 'useCases', 'components'));
    }

    public function storeConfig(Session $session, StoreSessionSutsRequest $request)
    {
        $components = collect($request->input('components'))->map(function ($item) {
           if (Arr::get($item, 'sut', false)) {
               return Arr::only($item, ['base_url']);
           }
        })->filter();
        $session->suts()->attach($components);

        return redirect()
            ->route('sessions.show', $session)
            ->with('success', __('Session created successfully'));
    }
}
