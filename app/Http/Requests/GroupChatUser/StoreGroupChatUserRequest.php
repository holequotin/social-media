<?php

namespace App\Http\Requests\GroupChatUser;

use App\Models\GroupChatUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupChatUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $groupChatId = $this->group_chat_id ?? null;
        return auth()->check() &&
            GroupChatUser::where('user_id', auth()->id())
                ->where('group_chat_id', $groupChatId)
                ->exists();
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
            'users' => ['required', 'array', 'min:1'],
            'users.*' => ['required', 'exists:users,id', 'unique:group_chat_users,user_id,NULL,id,group_chat_id,' . $this->group_chat_id],
        ];
    }
}
