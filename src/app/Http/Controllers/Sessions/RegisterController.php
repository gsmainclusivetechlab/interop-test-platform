<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreSessionSutsRequest;
use App\Http\Requests\Sessions\StoreSessionRequest;
use App\Http\Requests\Sessions\UpdateSessionRequest;
use App\Http\Resources\ScenarioResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Session;
use App\Models\Scenario;
use Illuminate\Support\Arr;
use Inertia\Inertia;

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
     * @return \Inertia\Response
     */
    public function create()
    {
        $scenario = Scenario::firstOrFail()->load(['components']);

        return Inertia::render('sessions/register/create', [
            'scenario' => (new ScenarioResource($scenario->load(['components'])))->resolve(),
            'useCases' => UseCaseResource::collection(
                $scenario->useCases()
                    ->with(['testCases'])
                    ->whereHas('testCases')
                    ->get()
            ),
        ]);
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
     * @return \Inertia\Response
     */
    public function edit(Session $session)
    {
        return Inertia::render('sessions.register.edit', [
            'scenario' => (new ScenarioResource($session->scenario->load(['components'])))->resolve(),
            'session' => (new SessionResource($session))->resolve(),
            'useCases' => UseCaseResource::collection(
                $session->scenario->useCases()
                    ->with(['testCases'])
                    ->whereHas('testCases')
                    ->get()
            ),
        ]);
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
        $scenario = $session->scenario->load(['components']);;
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
