<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User user()
 * @property \App\Models\User $user
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  => [
                'sometimes',
                'string',
                'max:191',
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:191',
                Rule::unique(\App\Models\User::class, 'email')->ignore($this->user->email),
            ],
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'max:32',
            ],
        ];
    }

    /**
     * get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string'       => 'NAME_MUST_STRING',
            'name.max'          => 'NAME_EXCEEDS_191_CHARACTERS',
            'email.string'      => 'EMAIL_MUST_STRING',
            'email.email'       => 'EMAIL_INVALID',
            'email.max'         => 'EMAIL_EXCEEDS_191_CHARACTERS',
            'email.unique'      => 'EMAIL_ALREADY_EXISTS',
            'password.string'   => 'PASSWORD_MUST_STRING',
            'password.min'      => 'PASSWORT_LESS_THAN_8_CHARACTERS',
            'password.max'      => 'PASSWORD_EXCEEDS_32_CHARACTERS',
        ];
    }
}
