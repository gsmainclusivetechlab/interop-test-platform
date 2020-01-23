<?php

namespace App\Http\Controllers\Settings;

use App\Models\TestSession;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestSessionCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

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
        $sessions = TestSession::latest()->paginate();

        return view('settings.sessions.index', compact('sessions'));
    }

    /**
     * @return TestSessionCollection
     */
    public function grid()
    {
        Paginator::currentPageResolver(function () {
            return (request('start') / request('length')) + 1;
        });

        $query = TestSession::when(request('order'), function ($query, $order) {
            foreach ($order as $item) {
                $query->orderBy(Arr::get(request('columns'), $item['column'])['data'], $item['dir']);
            }

            return $query;
        })->when(request('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search['value']}%");
            return $query;
        })->paginate(request('length'));

        return new TestSessionCollection($query);
    }
}
