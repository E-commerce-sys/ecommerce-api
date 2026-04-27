<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseAddressRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.attributes.addressName' => 'address_name',
            'data.attributes.streetName' => 'street_name',
            'data.attributes.houseNumber' => 'house_number',
            'data.attributes.city' => 'city',
            'data.attributes.state' => 'state',
            'data.attributes.zipCode' => 'zip_code',
            'data.attributes.country' => 'country',
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
            'data.attributes.addressName.string' => 'The address name must be text.',
            'data.attributes.addressName.max' => 'The address name may not be longer than 32 characters.',
            'data.attributes.addressName.min' => 'The address name must be at least 3 characters.',
            'data.attributes.streetName.required' => 'The street name is required.',
            'data.attributes.streetName.string' => 'The street name must be text.',
            'data.attributes.streetName.max' => 'The street name may not be longer than 132 characters.',
            'data.attributes.streetName.min' => 'The street name must be at least 3 characters.',
            'data.attributes.houseNumber.string' => 'The house number must be text.',
            'data.attributes.houseNumber.max' => 'The house number may not be longer than 32 characters.',
            'data.attributes.houseNumber.min' => 'The house number must be at least 3 characters.',
            'data.attributes.city.required' => 'The city is required.',
            'data.attributes.city.string' => 'The city must be text.',
            'data.attributes.city.max' => 'The city may not be longer than 32 characters.',
            'data.attributes.city.min' => 'The city must be at least 3 characters.',
            'data.attributes.state.string' => 'The state must be text.',
            'data.attributes.state.max' => 'The state may not be longer than 32 characters.',
            'data.attributes.state.min' => 'The state must be at least 3 characters.',
            'data.attributes.zipCode.string' => 'The zip code must be text.',
            'data.attributes.zipCode.max' => 'The zip code may not be longer than 32 characters.',
            'data.attributes.zipCode.min' => 'The zip code must be at least 3 characters.',
            'data.attributes.country.string' => 'The country must be text.',
            'data.attributes.country.max' => 'The country may not be longer than 32 characters.',
            'data.attributes.country.min' => 'The country must be at least 3 characters.',
        ];
    }
}
