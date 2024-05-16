<?php

namespace App\Http\Requests\Post;

use App\Enums\PostType;
use App\Repositories\PostImage\PostImageRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePostRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    public function __construct(protected PostImageRepositoryInterface $postImageRepository)
    {
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->mergeIfMissing(['images' => []]);
        $this->mergeIfMissing(['delete_image_id' => []]);
        return [
            'body' => ['string', 'nullable'],
            'type' => ['string', 'in:' . implode(',', PostType::getValues())],
            'images' => ['max:4'],
            'images.*' => ['image', 'max:2048'],
            'delete_image_id' => ['array', 'max:4'],
            'delete_image_id.*' => ['exists:post_images,id']
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $this->validated();
                $update_image_count = count($validated['images']);
                $delete_image_count = count($validated['delete_image_id']);

                if (!$this->checkValidImageCount($update_image_count, $delete_image_count)) {
                    $validator->errors()->add(
                        'images',
                        __('common.post.images.max', ['max' => config('define.post.images.max_count')])
                    );
                }

                //check if Post have PostImage
                $post = $this->route('post');
                if(!$this->postImageRepository->checkValidPostImage($post->id,$validated['delete_image_id'])){
                    $validator->errors()->add(
                        'delete_image_id',
                        __('common.post.images.invalid')
                    );
                }
            }
        ];
    }

    public function checkValidImageCount($update_image_count, $delete_image_count)
    {
        $post = $this->route('post');
        $image_count = $this->postImageRepository->getImageCountByPost($post->id);
        return $image_count + $update_image_count - $delete_image_count <= config('define.post.images.max_count');
    }
}
