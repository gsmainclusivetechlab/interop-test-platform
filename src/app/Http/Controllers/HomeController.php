<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Client\PendingRequest;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $request = Http::prepare()
            /*->mapRequest(function ($request) {
                $request = $request->withUri(new Uri('http://docs.guzzlephp.orgd'));

                return $request;
            })*/;
        dd($request->post('https://medium.com/@orobogenius/extending-core-laravel-bindings-97f409140fc3'));
        $sessions = auth()->user()->sessions()
            ->with(['testCases', 'lastTestRun'])
            ->latest()
            ->paginate(12);

        return view('home', compact('sessions'));
    }
}
