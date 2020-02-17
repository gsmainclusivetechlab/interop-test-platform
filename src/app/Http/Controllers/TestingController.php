<?php declare(strict_types=1);

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;

class TestingController extends Controller
{
    public function handle(ServerRequestInterface $request)
    {
        dd($request);

        $client = new Client();

        try {
            $response = $client->send($request, []);
            return $response;
        } catch (RequestException $e) {
            return $e->getResponse() ?: $e;
        }
    }
}
