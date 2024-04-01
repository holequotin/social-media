<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SharePostRequest extends FormRequest
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
            'body' => ['string'],
            'type' => ['string', 'required', 'in:' . implode(',', PostType::getValues())],
            'shared_post_id' => ['required', 'unique:posts,shared_post_id,NULL,id,user_id,' . auth()->id()],
        ];
    }
}
