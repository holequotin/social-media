<?php

namespace App\Http\Requests\GroupChatMessage;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupChatMessageRequest extends FormRequest
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
            'group_chat_id' => ['required', 'exists:group_chats,id', 'exists:group_chat_users,group_chat_id,user_id,' . auth()->id()],
            'body' => ['required', 'string']
        ];
    }
}
