<?php

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;

class MmoController extends Controller
{
    public function quotations(Request $request)
    {
        dd(1);

        $client = new Client();
        $psrRequest = $request->convertToPsr()
            ->withUri(new Uri('http://gsma-itp-mmo-api.develop.s8.jc/quotations'));

        try {
            $psrResponse = $client->send($psrRequest);
            return $psrResponse;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
