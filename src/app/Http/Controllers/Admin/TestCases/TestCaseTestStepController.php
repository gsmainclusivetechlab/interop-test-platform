<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\TestCases;

use App\Http\Resources\{
    ApiSpecResource,
    ComponentResource,
    TestCaseResource,
    TestStepResource
};
use App\Models\{ApiSpec, Component, TestCase, TestStep};
use App\Enums\HttpMethod;
use App\Enums\HttpStatus;
use App\Extensions\Twig\Uuid;
use App\Http\Controllers\Controller;
use App\Http\Requests\TestStepRequest;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

class TestCaseTestStepController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('test-case.latest')->only(['index', 'create']);
        $this->authorizeResource(TestStep::class, 'test_step', [
            'except' => ['show'],
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function index(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
////        $generated = \Blade::compileString('@verbatim {{ steps[0].body.amount.amount + steps[0].body.amount.amount }} @endverbatim');
//        $generated = \Blade::compileString('{!! Str::uuid()."\n".now() !!}');
////        $generated = \Blade::compileString('{!! \App\Models\TestCase::where("id", 144)->update(["draft" => false]) !!}');
//
//        ob_start() and extract(['steps' => [
//            ['headers' => ['ac' => 'ap/js'], 'body' => ['amount' => ['amount' => 5, 'currency' => 'UAH']]],
//            ['headers' => ['ac' => 'ap/js'], 'body' => ['amount' => ['amount' => 13, 'currency' => 'UAH']]],
//        ]], EXTR_SKIP);
//
//        // We'll include the view contents for parsing within a catcher
//        // so we can avoid any WSOD errors. If an exception occurs we
//        // will throw it out to the exception handler.
//        try
//        {
/*            eval('?>'.$generated);*/
//        }
//
//            // If we caught an exception, we'll silently flush the output
//            // buffer so that no partially rendered views get thrown out
//            // to the client and confuse the user with junk.
//        catch (\Exception $e)
//        {
//            ob_get_clean(); throw $e;
//        }
//
//        $content = ob_get_clean();
//
//        dd($content);

        $twig = new Environment(new ArrayLoader());
        $twig->addExtension(new Uuid());
//        $template = $twig->createTemplate('{{ steps.0.body.amount.amount + steps.1.body.amount.amount }}');
//        $template = $twig->createTemplate('{{ post.published_at|date_modify("+1 day")|date("d/m/Y") }}');
        $template = $twig->createTemplate('{{ uuidv4() }}');
        dd($template->render(
            [
                'steps' => [
                    ['headers' => ['ac' => 'ap/js'], 'body' => ['amount' => ['amount' => 5, 'currency' => 'UAH']]],
                    ['headers' => ['ac' => 'ap/js'], 'body' => ['amount' => ['amount' => 13, 'currency' => 'UAH']]],
                ],
                'post' => ['published_at' => now()]
            ]
        ));

        return Inertia::render('admin/test-cases/test-steps/index', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'testSteps' => TestStepResource::collection(
                $testCase
                    ->testSteps()
                    ->with(['source', 'target'])
                    ->get()
            ),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function create(TestCase $testCase)
    {
        $this->authorize('update', $testCase);
        return Inertia::render('admin/test-cases/test-steps/create', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::with('connections')->get()
            )->resolve(),
            'apiSpecs' => ApiSpecResource::collection(
                ApiSpec::get()
            )->resolve(),
            'methods' => HttpMethod::list(),
            'statuses' => HttpStatus::list(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param TestStepRequest $testStepRequest
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function store(TestCase $testCase, TestStepRequest $testStepRequest)
    {
        $this->authorize('update', $testCase);

        try {
            $testStepRequest->createTestStep($testCase);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.test-cases.test-steps.index', $testCase->id)
            ->with('success', __('Test Step created successfully'));
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function edit(TestCase $testCase, TestStep $testStep)
    {
        if (!$testCase->isLast()) {
            return redirect()->route(
                'admin.test-cases.test-steps.index',
                $testCase->last_version->id
            );
        }
        $this->authorize('update', $testCase);
        $testStep = $testCase
            ->testSteps()
            ->whereKey($testStep->getKey())
            ->firstOrFail();

        return Inertia::render('admin/test-cases/test-steps/edit', [
            'testCase' => (new TestCaseResource(
                $testCase->load(['testSteps'])
            ))->resolve(),
            'testStep' => (new TestStepResource(
                $testStep->load([
                    'source',
                    'target',
                    'apiSpec',
                    'testSetups',
                    'testScripts',
                ])
            ))->resolve(),
            'components' => ComponentResource::collection(
                Component::with('connections')->get()
            )->resolve(),
            'apiSpecs' => ApiSpecResource::collection(
                ApiSpec::get()
            )->resolve(),
            'methods' => HttpMethod::list(),
            'statuses' => HttpStatus::list(),
        ]);
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @param TestStepRequest $testStepRequest
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function update(
        TestCase $testCase,
        TestStep $testStep,
        TestStepRequest $testStepRequest
    ) {
        $this->authorize('update', $testCase);
        $testStep = $testCase
            ->testSteps()
            ->whereKey($testStep->getKey())
            ->firstOrFail();

        try {
            $testStepRequest->updateTestStep($testStep);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.test-cases.test-steps.index', $testCase->id)
            ->with('success', __('Test Step updated successfully'));
    }

    /**
     * @param TestCase $testCase
     * @param TestStep $testStep
     * @return RedirectResponse|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(TestCase $testCase, TestStep $testStep)
    {
        $this->authorize('update', $testCase);
        $testStep = $testCase
            ->testSteps()
            ->whereKey($testStep->getKey())
            ->firstOrFail();
        $testStep->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                __('Test step deleted successfully from test case')
            );
    }
}
