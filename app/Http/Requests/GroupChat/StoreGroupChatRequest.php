<?php

namespace App\Http\Requests\GroupChat;

use App\Enums\UserStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupChatRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'users' => ['array'],
            'users.*' => ['required', 'exists:users,id,status,' . UserStatus::ACTIVE, 'not_in:' . auth()->id(), 'distinct'],
        ];
    }
}
