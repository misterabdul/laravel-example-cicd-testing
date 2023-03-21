<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User user()
 */
class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'category' => [
                'required',
                'string',
                'uuid',
                Rule::exists(\App\Models\Category::class, 'id'),
            ],
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                'max:191',
                Rule::unique(\App\Models\Post::class, 'slug'),
            ],
            'title' => [
                'required',
                'string',
                'max:191',
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
            'category.required' => 'CATEGORY_REQUIRED',
            'category.string'   => 'CATEGORY_MUST_STRING',
            'category.uuid'     => 'CATEGORY_MUST_VALID_UUID',
            'category.exists'   => 'CATEGORY_NOT_EXISTS',
            'slug.required'     => 'SLUG_REQUIRED',
            'slug.string'       => 'SLUG_MUST_STRING',
            'slug.alpha_dash'   => 'SLUG_MUST_ALPHA_DASH_ONLY',
            'slug.max'          => 'SLUG_EXCEEDS_191_CHARACTERS',
            'slug.unique'       => 'SLUG_ALREADY_EXISTS',
            'title.required'    => 'TITLE_REQUIRED',
            'title.string'      => 'TITLE_MUST_STRING',
            'title.max'         => 'TITLE_EXCEEDS_191_CHARACTERS',
            'content.required'  => 'CONTENT_REQUIRED',
            'content.string'    => 'CONTENT_MUST_STRING',
        ];
    }
}
