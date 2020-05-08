<?php declare(strict_types=1);

namespace App\Http\Controllers;

class DarkModeController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        return redirect()->back()
            ->withCookie(cookie()->forever('dark_mode', !request()->cookie('dark_mode', false)));
    }
}
