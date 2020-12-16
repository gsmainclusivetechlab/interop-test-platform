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
     * @param int $subject
     * @param int $type
     * @param array $meta
     */
    public function __construct(Request $request, string $action, ?int $subject, int $type, ?array $meta)
    {
        $this->log($request, $action, $subject, $type, $meta);
    }

    /**
     * @param Request $request
     * @param string $action
     * @param int $subject
     * @param int $type
     * @param array $meta

     * @return null
     */
    public function log(Request $request, string $action, ?int $subject, int $type, ?array $meta)
    {
        $log = new AuditLog;
        $log->fullname_id = $request->user()->id;
        $log->action = $action;
        $log->subject = $subject;
        $log->type = $type;
        $log->meta = $meta;
        $log->save();

        return null;
    }
}
