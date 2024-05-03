<?php

namespace App\Http\Requests\GroupChatUser;

use App\Enums\GroupChatRole;
use App\Models\GroupChatUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupChatRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() &&
            GroupChatUser::where('user_id', auth()->id())
                ->where('group_chat_id', $this->groupChatUser->group_chat_id)
                ->where('role', GroupChatRole::ADMIN)
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
            'role' => ['required', 'in:' . implode(',', GroupChatRole::getValues())]
        ];
    }
}
