<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCartItemRequest extends FormRequest
{
     public function mappedAttributes() : array {
        $attributeMap = [
            'data.attributes.quantity' => 'quantity',
            'data.relationships.product.data.id' => 'product_id',
            'data.relationships.productColor.data.id' => 'product_color_id',
            'data.relationships.productSize.data.id' => 'product_size_id',
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

    public function messages()
    {
        return [
            'data.relationships.product.data.id.unique' => 'The product has already been added to your cart.',
            'data.relationships.productColor.data.id.required' => 'Please select a color.',
            'data.relationships.productSize.data.id.required' => 'Please select a size.',
        ];
    }
}
