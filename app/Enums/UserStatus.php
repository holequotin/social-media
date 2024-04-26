<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatus extends Enum
{
    const BLOCKED = "0";
    const ACTIVE = "1";
}
