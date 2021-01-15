<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return Response
     */
    public function showProfileForm()
    {
        return Inertia::render('settings/profile');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
        ]);
        $user = auth()->user();
        $user->update($request->input());

        return redirect()
            ->back()
            ->with('success', __('Profile updated successfully'));
    }
}
