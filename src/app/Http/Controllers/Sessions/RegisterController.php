<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\UseCase;
use App\Models\Scenario;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * SessionRegisterController constructor.
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
        $scenario = Scenario::firstOrFail();

        return view('sessions.register.selection', compact('scenario'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSelection()
    {
        return redirect()
            ->route('sessions.register.configuration.create');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createConfiguration()
    {
        $scenario = Scenario::firstOrFail();

        return view('sessions.register.configuration', compact('scenario'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeConfiguration()
    {
        return redirect()
            ->route('sessions.register.information.create');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createInformation()
    {
        $scenario = Scenario::firstOrFail();
        $useCases = UseCase::whereHas('testCases')->get();

        return view('sessions.register.information', compact('scenario', 'useCases'));
    }

    /**
     * @param StoreSessionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeInformation(StoreSessionRequest $request)
    {
        $session = DB::transaction(function () use ($request) {
            $user = auth()->user();
            $scenario = Scenario::firstOrFail();
            $session = $user->sessions()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'scenario_id' => $scenario->id,
            ]);
            $session->testCases()->attach($request->input('test_cases'));

            return $session;
        });

        return redirect()
            ->route('sessions.show', $session)
            ->with('success', __('Session created successfully'));
    }
}
