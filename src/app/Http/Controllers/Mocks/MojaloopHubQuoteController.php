<?php

namespace App\Http\Controllers\Mocks;

use App\Facades\Fsp;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class MojaloopHubQuoteController extends Controller
{
    public function store(ServerRequestInterface $request)
    {
        $fsp = Fsp::driver('mojaloop-hub');

        try {
            $response = $fsp->storeQuote($request->getBody(), collect($request->getHeaders())->except('host')->all());
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }

    public function callback(ServerRequestInterface $request)
    {
        return [];
    }
}
