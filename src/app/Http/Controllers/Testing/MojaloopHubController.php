<?php

namespace App\Http\Controllers\Testing;

use App\Facades\Fsp;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class MojaloopHubController extends Controller
{
    public function storeQuote(ServerRequestInterface $request)
    {
        $fsp = Fsp::driver('mojaloop-hub');

        try {
            $response = $fsp->storeQuote($request->getBody(), collect($request->getHeaders())->except('host')->all());
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }

    public function quotesCallback(ServerRequestInterface $request)
    {
        return [];
    }
}
