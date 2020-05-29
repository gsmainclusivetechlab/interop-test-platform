<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : Inertia::render('auth/verify');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with(
            'success',
            'A fresh verification link has been sent to your email address.'
        );
    }
}
