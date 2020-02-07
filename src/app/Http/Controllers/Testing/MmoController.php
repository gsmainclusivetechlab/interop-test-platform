<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ValidateTraceContext;
use App\Http\Middleware\SetJsonHeaders;
use App\Testing\RequestTest;
use App\Testing\TestRunner;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestSuite;

class MmoController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['api', ValidateTraceContext::class, SetJsonHeaders::class]);
    }

    public function quotations(Request $request)
    {
        $client = new Client();
        $request = $request->convertToPsr()->withUri(new Uri('http://gsma-itp-mmo-api.develop.s8.jc/quotations'));

        try {
            $response = $client->send($request);
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
