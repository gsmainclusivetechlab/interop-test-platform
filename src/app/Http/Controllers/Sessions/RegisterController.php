<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Component;
use App\Models\UseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Throwable;

class RegisterController extends Controller
{
    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(EnsureSessionIsPresent::class. ':session.sut')->only('info');
        $this->middleware(EnsureSessionIsPresent::class. ':session.sut,session.info')->only('config');
    }

    /**
     * @return \Inertia\Response
     */
    public function showSutForm()
    {
        return Inertia::render('sessions/register/sut', [
            'session' => session('session'),
            'suts' => ComponentResource::collection(
                Component::whereHas('testCases')->get(),
            ),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSut(Request $request)
    {
        $request->validate([
            'base_url' => ['required', 'url', 'max:255'],
            'component_id' => [
                'required',
                'exists:components,id',
            ],
        ]);
        $request->session()->put('session.sut', $request->input());

        return redirect()->route('sessions.register.info');
    }

    /**
     * @return \Inertia\Response
     */
    public function showInfoForm()
    {
        return Inertia::render('sessions/register/info', [
            'session' => session('session'),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'useCases' => UseCaseResource::collection(
                UseCase::with([
                    'testCases' => function ($query) {
                        $query->whereHas('components', function ($query) {
                            $query->where('id', request()->session()->get('session.sut.component_id'));
                        });
                    },
                ])->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeInfo(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'test_cases' => ['required', 'array', 'exists:test_cases,id'],
        ]);
        $request->session()->put('session.info', array_merge($request->input(), [
            'uuid' => Str::uuid(),
        ]));

        return redirect()->route('sessions.register.config');
    }

    /**
     * @return \Inertia\Response
     */
    public function showConfigForm()
    {
        return Inertia::render('sessions/register/config', [
            'session' => session('session'),
            'sut' => (new ComponentResource(
                Component::firstWhere('id', request()->session()->get('session.sut.component_id'))
                    ->load('connections'),
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeConfig(Request $request)
    {
        try {
            $session = DB::transaction(function () use ($request) {
                $session = auth()->user()
                    ->sessions()
                    ->create($request->session()->get('session.info'));
                $session->testCases()->attach($request->session()->get('session.info.test_cases'));
                $session->components()->attach([$request->session()->get('session.sut')]);

                return $session;
            });
            $request->session()->remove('session');

            return redirect()
                ->route('sessions.show', $session)
                ->with('success', __('Session created successfully'));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
