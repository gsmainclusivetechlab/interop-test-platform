<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UseCaseResource;
use App\Models\AuditLog;
use App\Models\Session;
use App\Models\UseCase;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Response
     * @throws AuthorizationException
     */
    public function admin()
    {
        $this->authorize('viewAny', AuditLog::class);
        return Inertia::render('admin/audit-log/index', [
            'logItems' => AuditLogResource::collection(
                AuditLog::with(['user'])
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @param Session $session
     * @return Response
     * @throws AuthorizationException
     */
    public function index(Session $session)
    {
    }
}
