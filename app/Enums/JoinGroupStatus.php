<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class JoinGroupStatus extends Enum
{
    const WAITING = "0";
    const JOINED = "1";
}
