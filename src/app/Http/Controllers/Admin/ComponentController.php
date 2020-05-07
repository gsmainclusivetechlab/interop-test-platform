<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ComponentResource;
use App\Models\Component;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class ComponentController extends Controller
{
    /**
     * ComponentController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Component::class, 'component');
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/components/index', [
            'components' => ComponentResource::collection(
                Component::when(request('q'), function (Builder $query, $q) {
                        $query->where('name', 'like', "%{$q}%");
                    })
                    ->paginate()
            ),
            'filter' => [
                'q' => request('q'),
            ],
        ]);
    }
}
