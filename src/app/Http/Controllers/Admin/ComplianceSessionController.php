<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ComplianceSessionController extends Controller
{
    /**
     * @param string|null $status
     *
     * @return \Inertia\Response
     */
    public function index($status = null)
    {
        return Inertia::render('admin/compliance-sessions/index', [
            'sessions' => SessionResource::collection(
                Session::whereHas('owner', function (Builder $query) {
                    $query->when(request('q'), function (Builder $query, $q) {
                        $query
                            ->whereRaw(
                                'CONCAT(first_name, " ", last_name) like ?',
                                "%{$q}%"
                            )
                            ->orWhere('name', 'like', "%{$q}%");
                    });
                })
                    ->with([
                        'owner',
                        'testCases' => function ($query) {
                            return $query->with(['useCase', 'lastTestRun']);
                        },
                        'lastTestRun',
                    ])
                    ->when($status, function (Builder $query, $status) {
                        $query->where('status', $status);
                    })
                    ->where('type', Session::TYPE_COMPLIANCE)
                    ->latest()
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
            'statusName' => $status ? Session::getStatusName($status) : null,
        ]);
    }

    public function update(Request $request, Session $session)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'status' => [
                'required',
                Rule::in([Session::STATUS_APPROVED, Session::STATUS_DECLINED]),
            ],
        ]);

        $session->update($data);

        return redirect()
            ->route('sessions.show', $session)
            ->with(
                'success',
                __(
                    $session->isStatusApproved()
                        ? 'Session approved successfully'
                        : 'Session declined successfully'
                )
            );
    }
}
