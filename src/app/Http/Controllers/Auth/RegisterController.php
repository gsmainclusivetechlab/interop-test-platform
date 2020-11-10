<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\GroupUserInvitation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showRegistrationForm(Request $request)
    {
        return Inertia::render('auth/register', [
            'invitationCode' => $request->query('invitationCode')
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        if ($request->invitation_code) {
            $user->markEmailAsVerified();
        }
        event(new Registered($user));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'company' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'terms' => ['required'],
            'invitation_code' => [
                'nullable',
                Rule::requiredIf(Env::get('REQUIRE_INVITED_REGISTRATION', false)),
                Rule::exists('group_user_invitations', 'invitation_code')->where(function ($query) use ($data) {
                    return $query
                        ->where('email', $data['email'])
                        ->where('expired_at', '>', Carbon::now()->toDateTimeString());
                }),
            ],
        ]);
    }

    /**
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param mixed $user
     * @return mixed
     * @throws Exception
     */
    protected function registered(Request $request, $user)
    {
        $invitations = GroupUserInvitation::where('email', $user->email)->get();

        foreach ($invitations as $invitation) {
            /** @var GroupUserInvitation $invitation */
            if (GroupUserInvitation::STATUS_ACTIVE === $invitation->status) {
                $groupUsers = $invitation->group->users();
                $groupUsers->attach($user->id, [
                    'admin' => !$groupUsers->exists(),
                ]);
            }
            $invitation->delete();
        }
    }
}
