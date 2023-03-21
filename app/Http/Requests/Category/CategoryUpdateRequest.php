<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User user()
 * @property \App\Models\Category $category
 */
class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->category);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'slug' => [
                'sometimes',
                'string',
                'alpha_dash',
                Rule::unique(\App\Models\Category::class, 'slug')->ignore($this->category->slug, 'slug'),
                'max:191'
            ],
            'name' => [
                'sometimes',
                'string',
                'max:191',
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:512',
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
            'slug.string'           => 'SLUG_MUST_STRING',
            'slug.alpha_dash'       => 'SLUG_MUST_ALPHA_DASH_ONLY',
            'slug.unique'           => 'SLUG_ALREADY_EXISTS',
            'slug.max'              => 'SLUG_EXCEEDS_191_CHARACTERS',
            'name.string'           => 'NAME_MUST_STRING',
            'name.max'              => 'NAME_EXCEEDS_191_CHARACTERS',
            'description.string'    => 'DESCRIPTION_MUST_STRING',
            'description.max'       => 'DESCRIPTION_EXCEEDS_512_CHARACTERS',
        ];
    }
}
