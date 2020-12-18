<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Enums\AuditActionEnum;
use App\Enums\AuditTypeEnum;
use App\Http\Controllers\Controller;
use App\Utils\AuditLogUtil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

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
     * @return Response
     */
    public function showPasswordForm()
    {
        return Inertia::render('settings/password');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ]);
        $user = auth()->user();
        $user->password = Hash::make($request->input('password'));
        $user->setRememberToken(Str::random(60));
        $user->save();

        new AuditLogUtil($request, AuditActionEnum::PASSWORD_RESET(),AuditTypeEnum::NO_TYPE, 0, null);

        return redirect()
            ->back()
            ->with('success', __('Password updated successfully'));
    }
}
