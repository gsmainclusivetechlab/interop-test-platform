<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreRegisterConfigurationRequest;
use App\Http\Requests\Sessions\StoreRegisterInformationRequest;
use App\Http\Requests\Sessions\StoreRegisterSelectionRequest;
use App\Models\TestScenario;
use App\Models\TestSession;

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
        $scenario = TestScenario::first();

        dd($scenario->components);
        return view('sessions.register.selection');
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
        return view('sessions.register.information');
    }

    /**
     * @param StoreRegisterInformationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeInformation(StoreRegisterInformationRequest $request)
    {
        $user = auth()->user();
        $session = $user->sessions()->create([
            'name' => $request->get('name'),
        ]);

        return redirect()
            ->route('sessions.index')
            ->with('success', __('Session created successfully'));
    }
}
