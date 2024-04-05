<?php

namespace App\Http\Requests\Post;

use App\Enums\JoinGroupStatus;
use App\Enums\PostType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        $this->mergeIfMissing(['images' => []]);
        return [
            'body' => ["required_without:images"],
            'images' => ["required_without:body",'max:4'],
            'images.*' => ['image', 'max:2048'],
            'type' => ['string', 'required', 'in:' . implode(',', PostType::getValues())],
            'group_id' => ['nullable', 'exists:groups,id', 'exists:group_user,group_id,user_id,' . auth()->id() . ',status,' . JoinGroupStatus::JOINED],
        ];
    }
}
