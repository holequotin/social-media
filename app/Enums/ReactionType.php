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
    const Like = "like";
    const Love = "love";
    const Haha = "haha";
    const Wow = "wow";
    const Sad = "sad";
}
