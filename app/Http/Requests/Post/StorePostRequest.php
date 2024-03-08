<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ["required_without:images"],
            'images' => ["required_without:body"],
            'images.*' => ['image', 'max:2048'],
            'type' => ['string', 'required', 'in:' . implode(',', PostType::getValues())],
        ];
    }
}
