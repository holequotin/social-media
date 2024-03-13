<?php

namespace App\Http\Requests\Reaction;

use App\Enums\ReactionType;
use App\Repositories\Reaction\ReactionRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReactionRequest extends FormRequest
{   
    public function __construct(protected ReactionRepositoryInterface $reactionRepository) {
    }
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
            'type' => ['string', 'required', 'in:' . implode(',', ReactionType::getValues())],
            'post_id' => ['required', 'exists:posts,id']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator){
                $validated = $this->validated();
                if ($this->checkReactionExist($validated)) {
                    $validator->errors()->add(
                        'reaction',
                        __('reaction.existed')
                    );
                }
            }
        ];
    }

    public function checkReactionExist($validated)
    {
        $reaction = $this->reactionRepository->getReactionByUserPost(auth()->user()->id, $validated['post_id']);
        return $reaction ? true : false;
    }
}
