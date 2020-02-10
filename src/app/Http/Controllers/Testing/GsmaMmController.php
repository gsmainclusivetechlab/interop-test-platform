<?php

namespace App\Http\Controllers\Testing;

use App\Facades\Fsp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;

class GsmaMmController extends Controller
{
    public function storeQuotation(Request $request)
    {
        $simulator = Fsp::driver('mojaloop-hub');

        dd($simulator->request('POST', 'quotes', [
            RequestOptions::BODY =>$request->getContent(),
            RequestOptions::HEADERS => $request->headers->all(),
        ])->getBody()->getContents());

        $simulator = Fsp::driver('gsma-mm');
        $psrRequest = $request->convertToPsr();

        try {
            $psrResponse = $simulator->request($psrRequest->getMethod(), 'quotations', $psrRequest->getHeaders(), $psrRequest->getBody());
            return $psrResponse;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
