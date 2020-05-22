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
            ->withCookie(request()->hasCookie('dark_mode') ? cookie()->forget('dark_mode') : cookie()->forever('dark_mode', true));
    }
}
