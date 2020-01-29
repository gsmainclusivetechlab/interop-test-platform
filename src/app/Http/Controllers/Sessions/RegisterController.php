<?php

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sessions\StoreRegisterConfigurationRequest;
use App\Http\Requests\Sessions\StoreRegisterInformationRequest;
use App\Http\Requests\Sessions\StoreRegisterSelectionRequest;
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

    public function createSelection()
    {
        return view('sessions.register.selection');
    }

    public function storeSelection(StoreRegisterSelectionRequest $request)
    {
        return redirect()
            ->route('sessions.register.configuration.create');
    }

    public function createConfiguration()
    {
        return view('sessions.register.configuration');
    }

    public function storeConfiguration(StoreRegisterConfigurationRequest $request)
    {
        return redirect()
            ->route('sessions.register.information.create');
    }

    public function createInformation()
    {
        return view('sessions.register.information');
    }

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
