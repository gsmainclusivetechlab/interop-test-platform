<?php

namespace App\Http\Controllers\Testing;

use App\Facades\Fsp;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class MojaloopHubController extends Controller
{
    public function storeQuote(Request $request)
    {
        $simulator = Fsp::driver('mojaloop-hub');
        $psrRequest = $request->convertToPsr();

        try {
            $psrResponse = $simulator->request($psrRequest->getMethod(), 'quotes', $psrRequest->getHeaders(), $psrRequest->getBody());
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
