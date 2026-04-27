<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseWishListItemRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
        $attributeMap = [
            'data.relationships.product.data.id' => 'product_id',
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
            'data.relationships.product.data.id.required' => 'Please choose a product to add to your wishlist.',
            'data.relationships.product.data.id.exists' => 'The selected wishlist product does not exist.',
            'data.relationships.product.data.id.unique' => 'The product has already been added to your wishlist.',
        ];
    }
}
