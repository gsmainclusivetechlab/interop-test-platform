<?php

namespace App\Http\Exports;

use App\Models\Session;
use App\Http\Exports\Contracts\ExportableReport;

class SessionExportFactory
{
    public static function resolveSessionExport(
        Session $session
    ): ExportableReport {
        switch ($session->type) {
            case Session::TYPE_COMPLIANCE:
                return app(ComplianceSessionExport::class);
            default:
                return app(BaseSessionExport::class);
        }
    }
}
