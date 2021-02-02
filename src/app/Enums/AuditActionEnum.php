<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static USER_CREATED()
 * @method static static USER_ROLE_CHANGED()
 * @method static static SESSION_CREATED()
 * @method static static SESSION_EDITED()
 * @method static static PASSWORD_RESET()
 * @method static static GROUP_ENVIRONMENT()
 * @method static static GROUP_INVITE()
 */
final class AuditActionEnum extends Enum
{
    const USER_CREATED = 'A user has been created';
    const USER_ROLE_CHANGED = "A user's role has been changed";
    const SESSION_CREATED = 'Created a Session';
    const SESSION_EDITED = 'Edited a Session';
    const PASSWORD_RESET = 'Password was reset';
    const GROUP_ENVIRONMENT = 'Created a new environment';
    const GROUP_INVITE = 'New user was invited to a group';
}
