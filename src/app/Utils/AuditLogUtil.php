<?php

namespace App\Utils;


use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Class AuditLogUtil
 * @package App\Utils
 */
class AuditLogUtil
{
    /**
     * @var array
     */
    protected $entry = [];

    /**
     * @param Request $request
     * @param string $action
     * @param string $subject
     * @param string $meta
     */
    public function __construct(Request $request, string $action, string $subject, string $meta)
    {
        $this->log($request, $action, $subject, $meta);
    }

    /**
     * @param Request $request
     * @param string $action
     * @param string $subject
     * @param string $meta

     * @return null
     */
    public function log(Request $request, string $action, string $subject, string $meta)
    {
        $log = new AuditLog;
        $log->actor = $request->user()->id;
        $log->action = $action;
        $log->subject = $subject;
        $log->meta = '[{}]';
        $log->save();

        return null;
    }
}
