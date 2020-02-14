<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('settings.password');
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->input('password'));
        $user->setRememberToken(Str::random(60));
        $user->save();

        return redirect()
            ->back()
            ->with('success', __('Password updated successfully'));
    }
}
