<?php

namespace App\Http\Exports;

use App\Models\TestCase;
use Illuminate\Support\Arr;
use App\Http\Requests\SessionRequest;
use App\Http\Resources\UseCaseResource;
use App\Models\QuestionnaireSection;
use App\Models\Session;
use App\Models\TestRun;
use App\Models\UseCase;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use App\Http\Exports\Contracts\ExportableReport;

class ComplianceSessionExport extends BaseSessionExport
{
    protected function pdfHeader(Section $section)
    {
        $section->addImage(
            resource_path('images/sessions/reports/compliance-logo.png'),
            '',
            false,
            config('app.name')
        );

        $this->title($section, 'Compliance Platform');
        $section->addText('&nbsp;');
    }

    protected function pdfInfoHeader(Section $section)
    {
        parent::pdfInfoHeader($section);

        if ($this->session->isStatusApproved()) {
            $this->line(
                $section,
                'Compliance Session approval date',
                $this->session->updated_at->format('F d, Y')
            );
        }

        $section->addText('&nbsp;');
    }
}
