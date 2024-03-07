<?php

namespace App\Http\Requests\Auth;

use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RefreshTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function __construct(protected AuthService $authService) {

    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'refresh_token' => ['required']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();
                if(!$this->authService->checkValidToken($validated['refresh_token'])){
                    $validator->errors()->add(
                        'refresh_token',
                        __('auth.token.invalid')
                    );
                }
            }
        ];
    }
}
