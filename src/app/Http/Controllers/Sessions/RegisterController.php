<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreRegisterConfigurationRequest;
use App\Http\Requests\Sessions\StoreRegisterInformationRequest;
use App\Http\Requests\Sessions\StoreRegisterSelectionRequest;
use App\Models\TestSuite;
use App\Models\TestScenario;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createSelection()
    {
        $scenario = TestScenario::firstOrFail();
        return view('sessions.register.selection', compact('scenario'));
    }

    /**
     * @param StoreRegisterSelectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSelection(StoreRegisterSelectionRequest $request)
    {
        return redirect()
            ->route('sessions.register.configuration.create');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createConfiguration()
    {
        return view('sessions.register.configuration');
    }

    /**
     * @param StoreRegisterConfigurationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeConfiguration(StoreRegisterConfigurationRequest $request)
    {
        return redirect()
            ->route('sessions.register.information.create');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createInformation()
    {
        $suites = TestSuite::whereHas('positiveCases')->orWhereHas('negativeCases')->get();

        return view('sessions.register.information', compact('suites'));
    }

    /**
     * @param StoreRegisterInformationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeInformation(StoreRegisterInformationRequest $request)
    {
        $user = auth()->user();
        $session = $user->sessions()->create([
            'name' => $request->input('name'),
            'scenario_id' => TestScenario::first()->value('id'),
        ]);
        $session->cases()->attach($request->input('cases'));

        return redirect()
            ->route('sessions.show', $session)
            ->with('success', __('Session created successfully'));
    }
}
