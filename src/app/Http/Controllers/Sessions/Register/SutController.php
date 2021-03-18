<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\ComponentResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Sessions\Register\Traits\{Queries, QuestionnaireKeys};
use App\Http\Requests\SessionSutRequest;
use App\Models\{Certificate, Group, ImplicitSut};
use Arr;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SutController extends Controller
{
    use Queries, QuestionnaireKeys;

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
        $groupCertificates = Certificate::getGroupCertificates();

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
            'hasGroupCertificates' => $groupCertificates->isNotEmpty(),
            'certificateIds' => collect(session('session.sut'))
                ->whereNotIn('certificate_id', $groupCertificates->pluck('id'))
                ->pluck('certificate_id')
                ->filter(),
        ]);
    }

    public function store(SessionSutRequest $request): RedirectResponse
    {
        $data = collect($request->get('components'))
            ->map(function ($sut, $key) use ($request) {
                $sut['use_encryption'] = $sut['use_encryption'] ?? false;
                $certificate = ($id = Arr::get($sut, 'certificate_id'))
                    ? Certificate::find($id)
                    : null;

                if (
                    (bool) $sut['use_encryption'] &&
                    (!$certificate || !$certificate->certificable_id)
                ) {
                    $sut['certificate_id'] = Certificate::updateOrCreate(
                        ['id' => $sut['certificate_id']],
                        Certificate::getCertificateAttribures(
                            $request,
                            $certificate,
                            "components.{$key}.ca_crt",
                            "components.{$key}.client_crt",
                            "components.{$key}.client_key",
                            Arr::get($sut, 'passphrase')
                        )
                    )->id;
                }

                return Arr::except($sut, [
                    'ca_crt',
                    'client_crt',
                    'client_key',
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
                    $query->whereHasMorph(
                        'certificable',
                        Group::class,
                        function (Builder $query) {
                            $query->whereHas('users', function (
                                Builder $query
                            ) {
                                $query->whereKey(
                                    auth()
                                        ->user()
                                        ->getAuthIdentifier()
                                );
                            });
                        }
                    );
                })
                ->latest()
                ->paginate()
        );
    }
}
