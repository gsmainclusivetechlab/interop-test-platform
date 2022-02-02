<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class TokenController extends Controller
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
    public function index()
    {
        return Inertia::render('settings/token');
    }

    /**
     * @return string
     */
    public function generate()
    {
        /** @var User $user */
        $user = auth()->user();
        $user->tokens()->delete();
        $token = $user->createToken('api');
        return $token->plainTextToken;
    }
}
