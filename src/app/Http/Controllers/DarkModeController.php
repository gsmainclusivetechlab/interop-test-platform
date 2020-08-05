<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DarkModeController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function __invoke()
    {
        return redirect()
            ->back()
            ->withCookie(
                request()->hasCookie('dark_mode')
                    ? cookie()->forget('dark_mode')
                    : cookie()->forever('dark_mode', true)
            );
    }
}
