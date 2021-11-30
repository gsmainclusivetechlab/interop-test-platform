<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\FaqResource;
use App\Imports\FaqImport;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\Yaml\Yaml;

class FaqController extends Controller
{
    /**
     * FaqController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Faq::class, 'faq', [
            'only' => ['index'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/faqs/index', [
            'faqs' =>  FaqResource::collection(
                Faq::latest()
                    ->paginate()
            ),
        ]);
    }

    /**
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showImportForm()
    {
        $this->authorize('create', Faq::class);
        return Inertia::render('admin/faqs/import', [
            'hasFaq' => Faq::exists()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function import(Request $request)
    {
        $this->authorize('create', Faq::class);
        $request->validate([
            'description' => ['string', 'nullable'],
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            (new FaqImport())->import($request);

            return redirect()
                ->route('admin.faqs.index')
                ->with('success', __('FAQ imported successfully. Don\'t forget to activate.'));
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'entries' => implode('<br>', array_merge(
                    ['Please resolve errors listed below:'],
                    $e->validator->errors()->all()
                )),
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Faq $faq
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Faq $faq)
    {
        $this->authorize('create', Faq::class);
        $faqData = Yaml::dump($faq->content, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        return response()->streamDownload(function () use ($faqData) { echo $faqData; }, 'faq.yaml');
    }

    /**
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggleActive(Faq $faq)
    {
        $this->authorize('toggleActive', $faq);
        $faq->timestamps = false;
        $faq->update(['active' => !$faq->active]);

        return redirect()->back();
    }
}
