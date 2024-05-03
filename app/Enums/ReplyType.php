<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ReplyType extends Enum
{
    const ACCEPT = 1;
    const REFUSE = 0;
}
