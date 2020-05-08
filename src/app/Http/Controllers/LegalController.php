<?php declare(strict_types=1);

namespace App\Http\Controllers;

class LegalController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptCookies()
    {
        return response()->json()->withCookie(cookie()->forever('cookies_accepted', true));
    }
}
