<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Inertia\Response
     */
    public function showLoginForm()
    {
        Session::flash('success', 'This is a message!');
        return Inertia::render('auth/login');
    }

    /**
     * @param Request $request
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($this->isCredentialsValid($request)) {
            throw ValidationException::withMessages([
                $this->username() => [__('Your account is blocked. Please contact us for more details.')],
            ]);
        } else {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isCredentialsValid(Request $request)
    {
        $password = User::withTrashed()
            ->where($this->username(), $request->get($this->username()))
            ->value('password');

        return $password && Hash::check($request->password, $password);
    }
}
