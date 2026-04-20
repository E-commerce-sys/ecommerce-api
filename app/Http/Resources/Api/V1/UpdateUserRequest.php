<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseUserRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UpdateUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data.attributes.firstName' => ['sometimes', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.lastName'  => ['sometimes', 'string', 'max:32', 'min:3', 'alpha'],
            'data.attributes.email'     => [
                'sometimes',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'
            ],

            'data.attributes.currentPassword' => [
                'required_with:data.attributes.password',
                'string',
            ],

            'data.attributes.password' => [
                'sometimes',
                'required_with:data.attributes.currentPassword',
                Password::min(8)
                    ->max(25)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],

            'data.attributes.password_confirmation' => [
                'required_with:data.attributes.password',
                'string',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->has('data.attributes.currentPassword')) {
                return;
            }

            $user = auth('sanctum')->user();
            $currentPassword = $this->input('data.attributes.currentPassword');

            if (!Hash::check($currentPassword, $user->password)) {
                $validator->errors()->add(
                    'data.attributes.currentPassword',
                    'The current password is incorrect.'
                );
            }

            $newPassword = $this->input('data.attributes.password');
            if ($newPassword && Hash::check($newPassword, $user->password)) {
                $validator->errors()->add(
                    'data.attributes.password',
                    'The new password must be different from the current password.'
                );
            }
        });
    }
}