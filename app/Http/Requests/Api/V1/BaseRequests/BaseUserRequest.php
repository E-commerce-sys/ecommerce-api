<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.attributes.firstName' => 'first_name',
            'data.attributes.lastName' => 'last_name',
            'data.attributes.email' => 'email',
            'data.attributes.password' => 'password',
            'data.attributes.address' => 'address',
            'data.relationships.mainAddress.data.id' => 'main_address_id',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $value = $this->input($key);

                if ($attribute === 'password') {
                    $value = bcrypt($value);
                }
                $attributesToUpdate[$attribute] = $value;
            }
        }

        return $attributesToUpdate;
    }

    public function messages(): array
    {
        return [
            'data.attributes.firstName.required' => 'The first name is required.',
            'data.attributes.firstName.string' => 'The first name must be text.',
            'data.attributes.firstName.max' => 'The first name may not be longer than 32 characters.',
            'data.attributes.firstName.min' => 'The first name must be at least 3 characters.',
            'data.attributes.firstName.alpha' => 'The first name may only contain letters.',
            'data.attributes.lastName.required' => 'The last name is required.',
            'data.attributes.lastName.string' => 'The last name must be text.',
            'data.attributes.lastName.max' => 'The last name may not be longer than 32 characters.',
            'data.attributes.lastName.min' => 'The last name must be at least 3 characters.',
            'data.attributes.lastName.alpha' => 'The last name may only contain letters.',
            'data.attributes.email.required' => 'The email address is required.',
            'data.attributes.email.string' => 'The email address must be text.',
            'data.attributes.email.email' => 'Please provide a valid email address.',
            'data.attributes.email.max' => 'The email address may not be longer than 255 characters.',
            'data.attributes.email.unique' => 'A user with this email address already exists.',
            'data.attributes.email.regex' => 'Please provide a complete email address, such as name@example.com.',
            'data.attributes.currentPassword.required_with' => 'The current password is required before setting a new password.',
            'data.attributes.currentPassword.string' => 'The current password must be text.',
            'data.attributes.password.required' => 'The password is required.',
            'data.attributes.password.required_with' => 'The new password is required when the current password is provided.',
            'data.attributes.password.min' => 'The password must be at least 8 characters.',
            'data.attributes.password.max' => 'The password may not be longer than 25 characters.',
            'data.attributes.password.password.mixed' => 'The password must include both uppercase and lowercase letters.',
            'data.attributes.password.password.numbers' => 'The password must include at least one number.',
            'data.attributes.password.password.symbols' => 'The password must include at least one symbol.',
            'data.attributes.password.confirmed' => 'The password confirmation does not match.',
            'data.attributes.password_confirmation.required_with' => 'Please confirm the new password.',
            'data.attributes.password_confirmation.string' => 'The password confirmation must be text.',
        ];
    }
}
