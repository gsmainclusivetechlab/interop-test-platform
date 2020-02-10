<?php

namespace App\Http\Controllers\Testing;

use App\Facades\Simulator;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class MojaloopController extends Controller
{
    public function quotes(Request $request)
    {
        $simulator = Simulator::driver('mojaloop');
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
