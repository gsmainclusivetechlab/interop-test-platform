<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * @return \Inertia\Response
     */
    public function showLinkRequestForm()
    {
        return Inertia::render('auth/passwords/email');
    }

    /**
     * @param Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return redirect('login')->with('success', trans($response));
    }
}
