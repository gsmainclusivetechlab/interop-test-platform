<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ApiSpecResource;
use App\Models\ApiSpec;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use cebe\openapi\Reader;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    /**
     * FaqController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Faq::class, 'faq', [
            'only' => ['index', 'destroy'],
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('admin/faqs/index', [
            'faqs' => []//Faq::all()
        ]);
    }

    /**
     * @return \Inertia\Response
     */
    public function showImportForm()
    {
        $this->authorize('create', ApiSpec::class);
        return Inertia::render('admin/faqs/import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function import(Request $request)
    {
        $this->authorize('create', ApiSpec::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            ApiSpec::create(
                array_merge(
                    ['name' => $request->input('name')],
                    $this->storeApiSpec($request->file('file'))
                )
            );

            return redirect()
                ->route('admin.faq.index')
                ->with('success', __('Faq created successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Faq $faq
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Faq $faq)
    {
        return response()->streamDownload(function () use ($faq) {
            echo \GuzzleHttp\json_encode($faq->content);
        }, 'faq');
    }

    /**
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggleActive(Faq $faq)
    {
        $this->authorize('toggleActive', $faq);
        $faq->update(['active' => !$faq->active]);

        return redirect()->back();
    }
}
