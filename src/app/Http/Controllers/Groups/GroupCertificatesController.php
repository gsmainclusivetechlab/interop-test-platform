<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Resources\{CertificateResource, GroupResource};
use App\Models\{Certificate, Group};
use App\Rules\SslCertificate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{RedirectResponse, Request};
use Inertia\{Inertia, Response};

class GroupCertificatesController extends Controller
{
    /**
     * @param Group $group
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Group $group)
    {
        $this->authorize('view', $group);

        return Inertia::render('groups/certificates/index', [
            'group' => GroupResource::make($group)->resolve(),
            'certificates' => CertificateResource::collection(
                $group
                    ->certificates()
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Group $group
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create(Group $group)
    {
        $this->authorize('admin', $group);

        return Inertia::render('groups/certificates/create', [
            'group' => GroupResource::make($group)->resolve(),
        ]);
    }

    /**
     * @param Request $request
     * @param Group $group
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request, Group $group)
    {
        $this->authorize('admin', $group);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ca_crt' => ['required', new SslCertificate()],
            'client_crt' => ['required', new SslCertificate()],
        ]);

        $group->certificates()->create([
            'name' => $request->get('name'),
            'ca_crt_path' => Certificate::storeFile($request, 'ca_crt'),
            'client_crt_path' => Certificate::storeFile($request, 'client_crt'),
        ]);

        return redirect()
            ->route('groups.certificates.index', $group)
            ->with('success', __('Certificate uploaded successfully'));
    }

    /**
     * @param Group $group
     * @param Certificate $certificate
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Group $group, Certificate $certificate)
    {
        $this->authorize('admin', $group);
        $certificate->delete();

        return redirect()
            ->back()
            ->with('success', __('Certificate deleted successfully'));
    }
}
