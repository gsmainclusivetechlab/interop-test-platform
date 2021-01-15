<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * @param String $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $locale = $request->input('locale');
        $locale =
            $locale && in_array($locale, config('app.locales'))
                ? $locale
                : \App::getFallbackLocale();
        if ($user = auth()->user()) {
            $user->update([
                'locale' => $locale,
            ]);
        }
        app()->setLocale($user->locale);

        return redirect()->back();
    }
}
