<?php

namespace App\Http\Requests\GroupUser;

use App\Enums\JoinGroupStatus;
use App\Enums\ShowPostType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShowPostTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $isOwner = $this->group->owner->is(auth()->user());
        $isAdmin = $this->group->admins->contains(auth()->user());
        $isJoined = $this->group->members()->wherePivot('status', JoinGroupStatus::JOINED)->wherePivot('user_id', $this->user->id)->exists();

        return ($isAdmin || $isOwner) && $isJoined;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:' . implode(',', ShowPostType::getValues())],
        ];
    }
}
