<?php

namespace App\Http\Requests\GroupUser;

use App\Enums\GroupRole;
use App\Enums\JoinGroupStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->group->owner_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'in:' . implode(',', [GroupRole::MEMBER, GroupRole::ADMIN])],
            'user_id' => [
                'required',
                'exists:users,id', 'exists:group_user,user_id,group_id,' . $this->group->id . ',status,' . JoinGroupStatus::JOINED,
                'not_in:' . auth()->id()
            ]
        ];
    }
}
