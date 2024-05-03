<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class GroupRole extends Enum
{
    const ADMIN = 0;
    const OWNER = 1;
    const MEMBER = 2;
}
