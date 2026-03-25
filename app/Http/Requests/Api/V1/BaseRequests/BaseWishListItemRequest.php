<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseWishListItemRequest extends FormRequest
{
    public function mappedAttributes() {
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

    public function messages()
    {
        return [
            'data.relationships.product.data.id.unique' => 'The product has already been added to your wishlist.',
        ];
    }
}
