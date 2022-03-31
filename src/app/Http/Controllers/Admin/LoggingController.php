<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\AuditLog;
use App\Models\MessageLog;
use Jackiedo\LogReader\LogReader;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppLogResource;
use App\Http\Resources\AuditLogResource;
use App\Http\Resources\MessageLogResource;
use App\Http\Resources\NginxErrorLogResource;
use App\Http\Resources\NginxAccessLogResource;
use App\Services\Logging\Parsers\NginxErrorLogParser;
use App\Services\Logging\Parsers\NginxAccessLogParser;

class LoggingController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MessageLog::class);

        return Inertia::render('admin/logs/message-log', [
            'logItems' => MessageLogResource::collection(
                MessageLog::with(['testRun', 'testCase', 'testStep', 'session'])
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    public function auditLog()
    {
        $this->authorize('viewAny', AuditLog::class);

        return Inertia::render('admin/logs/audit-log', [
            'logItems' => AuditLogResource::collection(
                AuditLog::with(['user'])
                    ->latest()
                    ->paginate()
            ),
        ]);
    }

    public function applicationLog(LogReader $logReader)
    {
        return Inertia::render('admin/logs/laravel-log', [
            'title' => __('Application log'),
            'logItems' => AppLogResource::collection(
                $logReader->filename('app.log')->paginate()
            ),
        ]);
    }

    public function queueLog(LogReader $logReader)
    {
        return Inertia::render('admin/logs/laravel-log', [
            'title' => __('Queue log'),
            'logItems' => AppLogResource::collection(
                $logReader->filename('queue.log')->paginate()
            ),
        ]);
    }

    public function nginxAccessLog(LogReader $logReader)
    {
        $logReader->setLogPath('/var/log/nginx');
        $logReader->setLogParser(new NginxAccessLogParser());

        return Inertia::render('admin/logs/nginx-access-log', [
            'logItems' => NginxAccessLogResource::collection(
                $logReader->filename('access.log')->paginate()
            ),
        ]);
    }

    public function nginxErrorLog(LogReader $logReader)
    {
        $logReader->setLogPath('/var/log/nginx');
        $logReader->setLogParser(new NginxErrorLogParser());

        return Inertia::render('admin/logs/nginx-error-log', [
            'logItems' => NginxErrorLogResource::collection(
                $logReader->filename('error.log')->paginate()
            ),
        ]);
    }
}
