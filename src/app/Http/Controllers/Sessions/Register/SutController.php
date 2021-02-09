<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\ComponentResource;
use App\Http\Controllers\Sessions\Register\Traits\{
    Queries,
    QuestionnaireKeys,
    SessionIds
};
use App\Http\Requests\SessionSutRequest;
use App\Models\{Certificate, Component};
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
                        'passphrase' => $sut['passphrase'],
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
}
