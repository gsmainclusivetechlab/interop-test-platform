<?php

namespace App\Http\Controllers\Settings;

use App\Http\DataTables\TestSessionTransformer;
use App\Models\TestSession;
use App\Http\Controllers\Controller;
use Yajra\DataTables\EloquentDataTable;

class SessionController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(TestSession::class, 'session');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('settings.sessions.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid()
    {
        $query = TestSession::query();
        $dataTable = EloquentDataTable::create($query);
        $dataTable->setTransformer(new TestSessionTransformer);

        dd($dataTable->toArray());

        return $dataTable->toJson();
    }
}
