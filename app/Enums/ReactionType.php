<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReactionType extends Enum
{
    const LIKE = "like";
    const LOVE = "love";
    const HAHA = "haha";
    const WOW = "wow";
    const SAD = "sad";
}
