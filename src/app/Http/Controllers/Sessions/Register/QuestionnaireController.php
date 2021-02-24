<?php

namespace App\Http\Controllers\Sessions\Register;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureSessionIsPresent;
use App\Http\Resources\{QuestionResource, SectionResource};
use App\Models\{QuestionnaireQuestions, QuestionnaireSection};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Validator;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            EnsureSessionIsPresent::class . ':session.type'
        )->only('index');
    }

    /**
     * @param QuestionnaireSection $section
     *
     * @return RedirectResponse|Response
     */
    public function index(QuestionnaireSection $section)
    {
        if (
            ($previous = QuestionnaireSection::previousSection($section->id)) &&
            !session()->exists("session.questionnaire.{$previous->id}")
        ) {
            return redirect()->route(
                'sessions.register.questionnaire',
                $previous
            );
        }

        return Inertia::render('sessions/register/questionnaire', [
            'previousSection' => $previous->id ?? null,
            'page' => QuestionnaireSection::where(
                'id',
                '<=',
                $section->id
            )->count(),
            'session' => session('session'),
            'sections' => SectionResource::collection(
                QuestionnaireSection::all()
            ),
            'questions' => QuestionResource::collection($section->questions),
        ]);
    }

    public function store(
        Request $request,
        QuestionnaireSection $section
    ): RedirectResponse {
        $rules = $this->questionnaireRules($section, $request->all());
        $validated = $request->validate($rules, [
            'required' => __('This question is required.'),
        ]);
        $request
            ->session()
            ->put("session.questionnaire.{$section->id}", $validated);

        return ($nextSection = QuestionnaireSection::nextSection($section->id))
            ? redirect()->route('sessions.register.questionnaire', $nextSection)
            : redirect()->route('sessions.register.questionnaire.summary');
    }

    public function summary(): Response
    {
        return Inertia::render('sessions/register/summary', [
            'session' => session('session'),
            'sections' => SectionResource::collection(
                QuestionnaireSection::with('questions')->get()
            ),
        ]);
    }

    protected function questionnaireRules(
        QuestionnaireSection $section,
        array $data
    ): array {
        $rules = [];
        foreach ($section->questions as $question) {
            if ($this->isRequiredAnswers($question, $data)) {
                $values = array_keys($question->values ?? []);

                switch ($question->type) {
                    case 'select':
                        $rule = Rule::in($values);
                        break;
                    case 'multiselect':
                        $rule = 'array';
                        break;
                    default:
                        $rule = 'string';
                }

                $rules[$question->name] = ['required', $rule];

                if ($question->isMultiSelect()) {
                    $rules["{$question->name}.*"] = [Rule::in($values)];
                }
            }
        }

        return $rules;
    }

    protected function isRequiredAnswers(
        QuestionnaireQuestions $question,
        array $data
    ): bool {
        foreach (
            $question->preconditions ?: []
            as $attribute => $preconditions
        ) {
            foreach ($preconditions as $rule => $precondition) {
                $precondition = (array) $precondition;
                if (isset($data[$attribute]) && is_array($data[$attribute])) {
                    $interection = array_uintersect(
                        $data[$attribute],
                        $precondition,
                        'strcasecmp'
                    );

                    return count($interection) > 0;
                }

                $validator = Validator::make($data, [
                    $attribute => [
                        'required',
                        "$rule:" . implode(',', $precondition),
                    ],
                ]);

                if ($validator->fails()) {
                    return false;
                }
            }
        }

        return true;
    }
}
