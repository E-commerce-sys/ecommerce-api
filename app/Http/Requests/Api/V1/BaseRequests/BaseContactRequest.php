<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseContactRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.message' => 'message',
            'data.attributes.phone' => 'phone',
        ];

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $value = $this->input($key);

                $attributesToUpdate[$attribute] = $value;
            }
        }

        return $attributesToUpdate;
    }

    public function messages(): array
    {
        return [
            'data.attributes.name.required' => 'The contact name is required.',
            'data.attributes.name.string' => 'The contact name must be text.',
            'data.attributes.name.alpha' => 'The contact name may only contain letters.',
            'data.attributes.name.min' => 'The contact name must be at least 2 characters.',
            'data.attributes.name.max' => 'The contact name may not be longer than 32 characters.',
            'data.attributes.email.required' => 'The contact email address is required.',
            'data.attributes.email.email' => 'Please provide a valid contact email address.',
            'data.attributes.phone.required' => 'The contact phone number is required.',
            'data.attributes.phone.digits_between' => 'The contact phone number must be between 6 and 15 digits.',
            'data.attributes.message.required' => 'The contact message is required.',
            'data.attributes.message.string' => 'The contact message must be text.',
            'data.attributes.message.max' => 'The contact message may not be longer than 500 characters.',
        ];
    }
}
