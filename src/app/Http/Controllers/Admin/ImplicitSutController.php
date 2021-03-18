<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImplicitSutRequest;
use App\Http\Resources\ImplicitSutResource;
use App\Models\Certificate;
use App\Models\ImplicitSut;
use DB;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ImplicitSutController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ImplicitSut::class, 'implicit_sut');
    }

    public function index(): Response
    {
        return Inertia::render('admin/implicit-suts/index', [
            'implicitSuts' => ImplicitSutResource::collection(
                ImplicitSut::query()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/implicit-suts/create');
    }

    public function store(ImplicitSutRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $implicitSut = ImplicitSut::create($request->validated());

            if ($implicitSut->use_encryption) {
                $implicitSut
                    ->certificate()
                    ->create(
                        Certificate::getCertificateAttribures(
                            $request,
                            $implicitSut->certificate
                        )
                    );
            }
        });

        return redirect()
            ->route('admin.implicit-suts.index')
            ->with('success', __('Implicit SUT rule created successfully'));
    }

    public function edit(ImplicitSut $implicitSut): Response
    {
        return Inertia::render('admin/implicit-suts/edit', [
            'implicitSut' => ImplicitSutResource::make(
                $implicitSut->load('certificate')
            )->resolve(),
        ]);
    }

    public function update(
        ImplicitSutRequest $request,
        ImplicitSut $implicitSut
    ): RedirectResponse {
        DB::transaction(function () use ($request, $implicitSut) {
            $implicitSut->update($request->validated());

            if ($implicitSut->use_encryption) {
                $implicitSut
                    ->certificate()
                    ->updateOrCreate(
                        [],
                        Certificate::getCertificateAttribures(
                            $request,
                            $implicitSut->certificate
                        )
                    );
            } elseif ($implicitSut->certificate) {
                $implicitSut->certificate->delete();
            }
        });

        return redirect()
            ->route('admin.implicit-suts.index')
            ->with('success', __('Implicit SUT rule updated successfully'));
    }

    public function destroy(ImplicitSut $implicitSut): RedirectResponse
    {
        $implicitSut->delete();

        return redirect()
            ->route('admin.implicit-suts.index')
            ->with('success', __('Implicit SUT rule deleted successfully'));
    }
}
