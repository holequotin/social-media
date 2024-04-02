<?php

namespace App\Rules;

use App\Repositories\Post\PostRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NestedLevel implements ValidationRule
{

    public function __construct(protected PostRepositoryInterface $postRepository)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $post = $this->postRepository->find($value);
        if ($this->postRepository->getSharedLevel($post) > config('define.post.max_share_level') - 1) {
            $fail(__('common.post.max_share_level', ['max' => config('define.post.max_share_level')]));
        }
    }
}
