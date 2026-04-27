<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseOrderRequest extends FormRequest
{
   public function mappedAttributes() : array {
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

    public function mappedAddressAttributes() : array {
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
}
