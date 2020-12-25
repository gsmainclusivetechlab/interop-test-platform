<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request)
    {
        if ($user = auth()->user()) {
            $user->update([
                'locale' => $request->input('locale')
            ]);
            app()->setLocale($user->locale);
        }

        return redirect()->back();
    }
}
