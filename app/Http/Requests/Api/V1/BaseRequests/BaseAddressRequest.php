<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseAddressRequest extends FormRequest
{
    public function mappedAttributes() : array {
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
}
