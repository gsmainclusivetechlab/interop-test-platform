<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @param Request $request
     * @param null $token
     * @return \Inertia\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        return Inertia::render('auth/passwords/reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * @param Request $request
     * @param string $response
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())->with(
            'success',
            trans($response)
        );
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8',
        ];
    }
}
