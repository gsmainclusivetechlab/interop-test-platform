<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

class MmoController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['api']);
        request()->headers->set('accept', 'applications/json');
    }

    public function quotations(ServerRequestInterface $request)
    {
        $client = new Client();
        $request = $request->withUri(new Uri('http://api-gateway.p73.skushnir.pers/quotations'));

        try {
            $response = $client->send($request);
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
