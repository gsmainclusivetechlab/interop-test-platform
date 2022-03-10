<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SESSION_CREATED()
 * @method static static SESSION_EDITED()
 * @method static static PASSWORD_RESET()
 * @method static static GROUP_ENVIRONMENT()
 * @method static static GROUP_INVITE()
 */
final class AuditActionEnum extends Enum
{
    const SESSION_CREATED = 'Created a Session';
    const SESSION_REPORT_CREATED = 'Created a Report of Session';
    const SESSION_EDITED = 'Edited a Session';
    const PASSWORD_RESET = 'Password was reset';
    const GROUP_ENVIRONMENT = 'Created a new environment';
    const GROUP_INVITE = 'New user was invited to a group';
}
