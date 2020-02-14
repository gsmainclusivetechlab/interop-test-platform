<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Environment;
use App\Http\Controllers\Controller;

class EnvironmentController extends Controller
{
    /**
     * EnvironmentController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(Environment::class, 'environment');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $environments = Environment::when(request('q'), function ($query, $q) {
            return $query->where('name', 'like', "%{$q}%");
        })->latest()->paginate();

        return view('admin.environments.index', compact('environments'));
    }

    /**
     * @param Environment $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Environment $environment)
    {
        return view('admin.environments.edit', compact('environment'));
    }
}
