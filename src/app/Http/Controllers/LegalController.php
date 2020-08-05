<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LegalController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function acceptCookies()
    {
        return response()
            ->json()
            ->withCookie(cookie()->forever('cookies_accepted', true));
    }
}
