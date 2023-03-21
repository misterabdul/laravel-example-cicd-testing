<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User user()
 * @property \App\Models\Post|null $post
 */
class CommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Comment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'post' => [
                Rule::requiredIf(fn () => $this->post !== null),
                'string',
                'uuid',
                Rule::exists(Post::class, 'id'),
            ],
            'content' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'post.requiredIf'   => 'POST_REQUIRED',
            'post.string'       => 'POST_MUST_STRING',
            'post.uuid'         => 'POST_MUST_VALID_UUID',
            'post.exists'       => 'POST_NOT_EXISTS',
            'content.required'  => 'CONTENT_REQUIRED',
            'content.string'    => 'CONTENT_MUST_STRING',
        ];
    }
}
