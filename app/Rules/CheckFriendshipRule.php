<?php

namespace App\Rules;

use App\Repositories\Friendship\FriendshipRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckFriendshipRule implements ValidationRule
{
    public function __construct(protected FriendshipRepositoryInterface $friendshipRepository) {
    }
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $friendship = $this->friendshipRepository->getFriendship(auth()->id(), $value);
        if(!$friendship){
            $fail(__('common.friendship.not_exist'));
        }
    }
}
