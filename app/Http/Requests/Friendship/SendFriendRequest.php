<?php

namespace App\Http\Requests\Friendship;

use Illuminate\Foundation\Http\FormRequest;

class SendFriendRequest extends FormRequest
{
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
            'to_user_id' => [
                'required',
                'exists:users,id',
                'unique:friendships,to_user_id,NULL,id,from_user_id,'.auth()->user()->id,
                'unique:friendships,from_user_id,NULL,id,to_user_id,'.auth()->user()->id,
            ]
        ];
    }
}
