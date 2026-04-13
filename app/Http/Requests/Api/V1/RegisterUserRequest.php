<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseUserRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends BaseUserRequest 
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'data.attributes.firstName' => ['required', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.lastName' => ['required', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->whereNull('deleted_at'),
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'
            ],
            'data.attributes.password' => [
                'required',
                 Password::min(8)
                 ->max(25)
                 ->mixedCase()
                 ->numbers()
                 ->symbols(),
                 'confirmed'
            ],
        ];
    }
}
