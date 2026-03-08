<?php

namespace App\Http\Requests\Api\v1;

use App\Http\Requests\Api\V1\BaseRequests\BaseUserRequest;

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
            'data.attributes.firstName' => ['required', 'string', 'max:255'],
            'data.attributes.lastName' => ['required', 'string', 'max:255'],
            'data.attributes.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'data.attributes.password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
