<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseUserRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends BaseUserRequest
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
            'data.attributes.firstName' => ['sometimes', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.lastName' => ['sometimes', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.email' => [
                'sometimes', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email',
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'
            ],
            'data.attributes.password' => [
                'sometimes',
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
