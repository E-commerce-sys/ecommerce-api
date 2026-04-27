<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseOrderRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.relationships.shippingAddress.data.id' => 'shipping_address_id',
            'data.attributes.status' => 'status',
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

    public function mappedAddressAttributes(): array
    {
        $attributeMap = [
            'data.included.address.attributes.addressName' => 'address_name',
            'data.included.address.attributes.city' => 'city',
            'data.included.address.attributes.streetName' => 'street_name',
            'data.included.address.attributes.houseNumber' => 'house_number',
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
            'data.relationships.shippingAddress.data.id.required' => 'Choose an existing shipping address when you are not creating a new one.',
            'data.relationships.shippingAddress.data.id.prohibited' => 'Do not send an existing shipping address when creating a new address for the order.',
            'data.relationships.shippingAddress.data.id.exists' => 'The selected shipping address does not exist.',
            'data.attributes.status.string' => 'The order status must be text.',
            'data.attributes.status.in' => 'The order status must be one of: pending, preparing, shipping, delivering, arrived, or cancelled.',
            'data.included.address.attributes.addressName.required' => 'The new shipping address name is required when creating a new address.',
            'data.included.address.attributes.addressName.prohibited' => 'Do not send a new address name when using an existing shipping address.',
            'data.included.address.attributes.addressName.string' => 'The new shipping address name must be text.',
            'data.included.address.attributes.addressName.max' => 'The new shipping address name may not be longer than 32 characters.',
            'data.included.address.attributes.addressName.min' => 'The new shipping address name must be at least 3 characters.',
            'data.included.address.attributes.city.required' => 'The new shipping address city is required when creating a new address.',
            'data.included.address.attributes.city.prohibited' => 'Do not send a new city when using an existing shipping address.',
            'data.included.address.attributes.city.string' => 'The new shipping address city must be text.',
            'data.included.address.attributes.city.max' => 'The new shipping address city may not be longer than 32 characters.',
            'data.included.address.attributes.city.min' => 'The new shipping address city must be at least 3 characters.',
            'data.included.address.attributes.city.alpha' => 'The new shipping address city may only contain letters.',
            'data.included.address.attributes.streetName.required' => 'The new shipping street name is required when creating a new address.',
            'data.included.address.attributes.streetName.prohibited' => 'Do not send a new street name when using an existing shipping address.',
            'data.included.address.attributes.streetName.string' => 'The new shipping street name must be text.',
            'data.included.address.attributes.streetName.max' => 'The new shipping street name may not be longer than 132 characters.',
            'data.included.address.attributes.streetName.min' => 'The new shipping street name must be at least 3 characters.',
            'data.included.address.attributes.houseNumber.string' => 'The new shipping house number must be text.',
            'data.included.address.attributes.houseNumber.alpha_num' => 'The new shipping house number may only contain letters and numbers.',
        ];
    }
}
