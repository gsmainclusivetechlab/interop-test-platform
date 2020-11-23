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
    protected $actions = [
        'Created a session',
        'Password was reset',
    ];

    /**
     * @param Request $request
     * @param string $action
     * @param string $subject
     * @param int $type
     * @param string $meta
     */
    public function __construct(Request $request, string $action, string $subject, int $type, string $meta)
    {
        $this->log($request, $action, $subject, $type, $meta);
    }

    /**
     * @param Request $request
     * @param string $action
     * @param string $subject
     * @param int $type
     * @param string $meta

     * @return null
     */
    public function log(Request $request, string $action, string $subject, int $type, string $meta)
    {
        $log = new AuditLog;
        $log->fullname = $request->user();
        $log->action = $action;
        $log->subject = $subject;
        $log->type = $type;
        $log->meta = '[{}]';
        $log->save();

        return null;
    }
}
