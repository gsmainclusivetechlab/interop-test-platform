<?php

namespace App\Http\Controllers\Mocks;

use App\Facades\Fsp;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class GsmaMmQuotationController extends Controller
{
    public function store(ServerRequestInterface $request)
    {
        $fsp = Fsp::driver('gsma-mm');

        try {
            $response = $fsp->storeQuotation($request->getBody(), collect($request->getHeaders())->except('host')->all());
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
