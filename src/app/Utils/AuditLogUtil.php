<?php

namespace App\Utils;

use App\Enums\AuditActionEnum;
use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Class AuditLogUtil
 * @package App\Utils
 */
class AuditLogUtil
{
    /**
     * @param Request $request
     * @param AuditActionEnum $action
     * @param int $type
     * @param int $subject
     * @param array $meta
     */
    public function __construct(
        Request $request,
        AuditActionEnum $action,
        int $type,
        ?int $subject,
        ?array $meta
    ) {
        $this->log($request, $action, $type, $subject, $meta);
    }

    /**
     * @param Request $request
     * @param AuditActionEnum $action
     * @param int $type
     * @param int $subject
     * @param array $meta

     * @return null
     */
    public function log(
        Request $request,
        AuditActionEnum $action,
        int $type,
        ?int $subject,
        ?array $meta
    ) {
        $log = new AuditLog();
        $log->user_id = $request->user()->id;
        $log->action = $action;
        $log->type = $type;
        $log->subject = $subject;
        $log->meta = $meta;
        $log->save();

        return null;
    }
}
