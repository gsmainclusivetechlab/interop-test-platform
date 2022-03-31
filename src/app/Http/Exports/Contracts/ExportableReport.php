<?php

namespace App\Http\Exports\Contracts;

use App\Models\Session;
use PhpOffice\PhpWord\PhpWord;

interface ExportableReport
{
    public function export(Session $session): PhpWord;

    public function exportPdf(Session $session, array $request): PhpWord;
}
