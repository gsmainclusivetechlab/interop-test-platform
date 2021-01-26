<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\QuestionnaireImport;
use App\Models\QuestionnaireSection;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Yaml\Yaml;

class QuestionnaireController extends Controller
{
    /**
     * @return Response
     */
    public function showImportForm()
    {
        $this->authorize('create', QuestionnaireSection::class);

        return Inertia::render('admin/questionnaire/import');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function import()
    {
        $this->authorize('create', QuestionnaireSection::class);

        request()->validate([
            'file' => ['required', 'mimetypes:text/yaml,text/plain'],
        ]);

        try {
            $rows = Yaml::parse(
                request()
                    ->file('file')
                    ->get()
            );

            (new QuestionnaireImport())->import($rows);

            return redirect()
                ->route('home')
                ->with('success', __('Questionnaire imported successfully'));
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
