<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class AuditTypeEnum extends Enum
{
    const NO_TYPE = 0;
    const SESSION_TYPE = 1;
    const GROUP_TYPE = 2;
}
