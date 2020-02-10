<?php

namespace App\Http\Controllers\Testing;

use App\Facades\Simulator;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class MmoController extends Controller
{
    public function quotations(Request $request)
    {
        $simulator = Simulator::driver('mobile-money');
        $psrRequest = $request->convertToPsr();

        try {
            $psrResponse = $simulator->request($psrRequest->getMethod(), 'quotations', $psrRequest->getHeaders(), $psrRequest->getBody());
            return $psrResponse;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
