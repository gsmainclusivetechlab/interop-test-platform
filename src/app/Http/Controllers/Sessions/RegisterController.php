<?php declare(strict_types=1);

namespace App\Http\Controllers\Sessions;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\GroupEnvironmentResource;
use App\Http\Resources\UseCaseResource;
use App\Models\Component;
use App\Models\GroupEnvironment;
use App\Models\TestCase;
use App\Models\UseCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class RegisterController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(EnsureSessionIsPresent::class . ':session.sut')->only(
            'info'
        );
        $this->middleware(
            EnsureSessionIsPresent::class . ':session.sut,session.info'
        )->only('config');
    }

    /**
     * @return Response
     */
    public function showSutForm()
    {
        return Inertia::render('sessions/register/sut', [
            'session' => session('session'),
            'suts' => ComponentResource::collection(
                Component::whereHas('testCases')->get()
            ),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeSut(Request $request)
    {
        $request->validate([
            'base_url' => ['required', 'url', 'max:255'],
            'component_id' => ['required', 'exists:components,id'],
        ]);
        $request->session()->put('session.sut', $request->input());

        return redirect()->route('sessions.register.info');
    }

    /**
     * @return Response
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
                        $query
                            ->whereHas('components', function ($query) {
                                $query->whereKey(
                                    request()
                                        ->session()
                                        ->get('session.sut.component_id')
                                );
                            })
                            ->where('public', true)
                            ->orWhereHas('owner', function ($query) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            })
                            ->orWhereHas('groups', function ($query) {
                                $query->whereHas('users', function ($query) {
                                    $query->whereKey(
                                        auth()
                                            ->user()
                                            ->getAuthIdentifier()
                                    );
                                });
                            })
                            ->when(
                                auth()
                                    ->user()
                                    ->can('viewAnyPrivate', TestCase::class),
                                function ($query) {
                                    $query->orWhere('public', false);
                                }
                            );
                    },
                ])
                    ->whereHas('testCases', function ($query) {
                        $query
                            ->whereHas('components', function ($query) {
                                $query->whereKey(
                                    request()
                                        ->session()
                                        ->get('session.sut.component_id')
                                );
                            })
                            ->when(
                                !auth()
                                    ->user()
                                    ->can('viewAny', TestCase::class),
                                function ($query) {
                                    $query->where('public', true);
                                }
                            );
                    })
                    ->get()
            ),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeInfo(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'nullable'],
            'test_cases' => ['required', 'array', 'exists:test_cases,id'],
        ]);
        $request->session()->put(
            'session.info',
            array_merge($request->input(), [
                'uuid' => Str::uuid(),
            ])
        );

        return redirect()->route('sessions.register.config');
    }

    /**
     * @return Response
     */
    public function showConfigForm()
    {
        return Inertia::render('sessions/register/config', [
            'session' => session('session'),
            'sut' => (new ComponentResource(
                Component::whereKey(
                    request()
                        ->session()
                        ->get('session.sut.component_id')
                )
                    ->firstOrFail()
                    ->load('connections')
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::with(['connections'])->get()
            ),
            'hasGroupEnvironments' => GroupEnvironment::whereHas('group', function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->whereKey(auth()->user()->getAuthIdentifier());
                    });
                })->exists(),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeConfig(Request $request)
    {
        $request->validate([
            'group_environment_id' => [
                'nullable',
                'exists:group_environments,id',
            ],
            'environments' => ['nullable', 'array'],
        ]);

        try {
            $session = DB::transaction(function () use ($request) {
                $session = auth()
                    ->user()
                    ->sessions()
                    ->create(
                        collect($request->session()->get('session.info'))
                            ->merge($request->input())
                            ->all()
                    );
                $session
                    ->testCases()
                    ->attach(
                        $request->session()->get('session.info.test_cases')
                    );
                $session
                    ->components()
                    ->attach([$request->session()->get('session.sut')]);

                return $session;
            });
            $request->session()->remove('session');

            return redirect()
                ->route('sessions.show', $session)
                ->with('success', __('Session created successfully'));
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function groupEnvironmentCandidates()
    {
        return GroupEnvironmentResource::collection(
            GroupEnvironment::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->whereHas('group', function (Builder $query) {
                    $query->whereHas('users', function (Builder $query) {
                        $query->whereKey(
                            auth()
                                ->user()
                                ->getAuthIdentifier()
                        );
                    });
                })
                ->latest()
                ->paginate()
        );
    }
}
