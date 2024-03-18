<?php

namespace App\Http\Requests\Friendship;

use App\Repositories\Friendship\FriendshipRepositoryInterface;
use App\Rules\CheckFriendshipRule;
use Illuminate\Foundation\Http\FormRequest;

class UnfriendRequest extends FormRequest
{
    public function __construct(protected FriendshipRepositoryInterface $friendshipRepository) {
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'friend_id' => [
                'required',
                'exists:users,id',
                new CheckFriendshipRule($this->friendshipRepository)
            ]
        ];
    }
}
