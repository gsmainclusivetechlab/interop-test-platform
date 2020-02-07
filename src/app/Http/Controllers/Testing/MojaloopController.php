<?php

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;

class MojaloopController extends Controller
{
    public function quotes(Request $request)
    {
        $client = new Client();
        $psrRequest = $request->convertToPsr()
            ->withUri(new Uri('http://quoting-service.mojaloop.staging.s4.justcoded.com/quotes'));

        try {
            $psrResponse = $client->send($psrRequest);
            return $psrResponse;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }

    public function quotesCallback(Request $request)
    {
        return [];
    }
}
