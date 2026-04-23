<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCouponRequest extends FormRequest
{
     public function mappedAttributes() : array {
        $attributeMap = [
            'data.relationships.user.data.id' => 'user_id',
            'data.attributes.code' => 'code',
            'data.attributes.discountPercentage' => 'discount_percentage',
            'data.attributes.expiresAt' => 'expires_at',
            'data.attributes.maxApplicablePrice' => 'max_applicable_price',

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
