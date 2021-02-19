<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\ComponentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Sessions\Register\Traits\{
    Queries,
    QuestionnaireKeys,
    SessionIds
};
use App\Http\Requests\SessionSutRequest;
use App\Models\{Certificate, Component, Group, ImplicitSut};
use Arr;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SutController extends Controller
{
    use Queries, SessionIds, QuestionnaireKeys;

    public function __construct()
    {
        $this->middleware(
            EnsureSessionIsPresent::class .
                ":session.type,session.info.name{$this->getQuestionnaireKeys()}"
        )->only('index');
    }

    public function index(): Response
    {
        $components = $this->getComponents();

        return Inertia::render('sessions/register/sut', [
            'session' => session('session'),
            'components' => ComponentResource::collection($components),
            'versions' => $this->getVersions($components),
            'implicitSuts' => ImplicitSut::whereIn(
                'slug',
                $components->pluck('slug')
            )
                ->get()
                ->groupBy('slug'),
            'hasGroupCertificates' =>
                Certificate::hasGroupCertificates() || $this->getSessionIds(),
        ]);
    }

    public function store(SessionSutRequest $request): RedirectResponse
    {
        $data = collect($request->get('components'))
            ->map(function ($sut, $key) use ($request) {
                $sut['use_encryption'] = $sut['use_encryption'] ?? false;

                if (
                    (bool) $sut['use_encryption'] &&
                    !Arr::get($sut, 'certificate_id')
                ) {
                    $sut['certificate_id'] = Certificate::create([
                        'passphrase' => Arr::get($sut, 'passphrase'),
                        'name' => Component::find($sut['id'])->name,
                        'ca_crt_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.ca_crt"
                        ),
                        'client_crt_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.client_crt"
                        ),
                        'client_key_path' => Certificate::storeFile(
                            $request,
                            "components.{$key}.client_key"
                        ),
                    ])->id;
                }

                return Arr::except($sut, [
                    'ca_crt',
                    'client_crt',
                    'client_key',
                    'passphrase',
                ]);
            })
            ->all();

        $request->session()->put('session.sut', $data);

        return redirect()->route('sessions.register.config');
    }

    public function groupCertificateCandidates(): AnonymousResourceCollection
    {
        return CertificateResource::collection(
            Certificate::when(request('q'), function (Builder $query, $q) {
                $query->whereRaw('name like ?', "%{$q}%");
            })
                ->where(function (Builder $query) {
                    $query
                        ->whereHasMorph('certificable', Group::class, function (
                            Builder $query
                        ) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        })
                        ->when($this->getSessionIds(), function (
                            Builder $query,
                            $ids
                        ) {
                            $query->orWhereIn('id', $ids);
                        })
                        ->when(request('session'), function (
                            Builder $query,
                            $session
                        ) {
                            $query->orWhereHas('sessions', function (
                                Builder $query
                            ) use ($session) {
                                $query->whereKey($session);
                            });
                        });
                })
                ->latest()
                ->paginate()
        );
    }
}
