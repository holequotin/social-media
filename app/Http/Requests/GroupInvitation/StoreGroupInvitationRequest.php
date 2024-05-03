<?php

namespace App\Http\Requests\GroupInvitation;

use App\Enums\JoinGroupStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupInvitationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'exists:groups,id', 'exists:group_user,group_id,user_id,' . auth()->id() . ',status,' . JoinGroupStatus::JOINED],
            'be_invite_id' => [
                'required',
                'exists:users,id',
                'unique:group_user,user_id,NULL,id,group_id,' . $this->group_id,
                'unique:group_invitations,be_invite_id,NULL,id,group_id,' . $this->group_id
            ]
        ];
    }
}
