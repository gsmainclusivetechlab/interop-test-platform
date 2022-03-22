<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Exceptions\MessageMismatchException;
use App\Http\Controllers\Controller;
use App\Models\{QuestionnaireSection, Session};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Inertia\{Inertia, Response};
use Validator;

class TypeController extends Controller
{
    /**
     * @return RedirectResponse|Response
     */
    public function index()
    {
        session()->forget('session');
        if(!auth()->user()->isAdmin()){
            $availableModes = config('service_session.available_modes');
            $userAvailableModes = [];
            foreach(auth()->user()->groups->toArray() as $group){
                foreach($group['session_available'] as $available){
                    if(!isset($userAvailableModes[$available])){
                        $userAvailableModes[$available] = $availableModes[$available];
                    }
                }
                if(count($userAvailableModes) == 3) break;
            }

        } else {
            $userAvailableModes = config('service_session.available_modes');
        }

        $filteredAvailableModes = collect ($userAvailableModes )
            ->filter()
            ->all();

        if (count($filteredAvailableModes) == 1) {
            switch ($key = array_key_first($filteredAvailableModes)) {
                case 'test':
                case 'test_questionnaire':
                    $type = Session::TYPE_TEST;
                    break;
                case 'compliance':
                    $type = Session::TYPE_COMPLIANCE;
                    break;
                default:
                    throw new MessageMismatchException(
                        null,
                        400,
                        'The available mode does not match any session type'
                    );
            }

            return $this->resolveSessionType(
                $type,
                $key == 'test_questionnaire'
            );
        }

        return Inertia::render('sessions/register/type', [
            'session' => session('session'),
            'testRunAttempts' => config(
                'service_session.compliance_session_execution_limit'
            ),
            'availableModes' => $userAvailableModes,
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $withQuestions = (bool) $request->get('withQuestions');

        Validator::validate(
            [
                'type' => $type,
            ],
            [
                'type' => [
                    'required',
                    Rule::in(array_keys(Session::getTypeNames())),
                ],
            ]
        );

        return $this->resolveSessionType($type, $withQuestions);
    }

    protected function resolveSessionType(
        string $type,
        bool $withQuestions
    ): RedirectResponse {
        session()->put([
            'session.type' => $type,
            'session.withQuestions' => $withQuestions,
        ]);

        return Session::isCompliance($type) || $withQuestions
            ? redirect()->route(
                'sessions.register.questionnaire',
                QuestionnaireSection::query()->first()
            )
            : redirect()->route('sessions.register.info');
    }
}
