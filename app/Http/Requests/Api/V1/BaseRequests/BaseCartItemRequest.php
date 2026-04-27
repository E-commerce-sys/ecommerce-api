<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCartItemRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.attributes.quantity' => 'quantity',
            'data.relationships.productVariant.data.id' => 'product_variant_id',
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
            'data.attributes.quantity.required' => 'The cart item quantity is required.',
            'data.attributes.quantity.integer' => 'The cart item quantity must be a whole number.',
            'data.attributes.quantity.min' => 'The cart item quantity must be at least 1.',
            'data.attributes.quantity.max' => 'The cart item quantity may not be greater than 100.',
            'data.relationships.productVariant.data.id.required' => 'Please choose a product variant to add to the cart.',
            'data.relationships.productVariant.data.id.integer' => 'The product variant id must be a whole number.',
            'data.relationships.productVariant.data.id.exists' => 'The selected product variant does not exist.',
        ];
    }
}
