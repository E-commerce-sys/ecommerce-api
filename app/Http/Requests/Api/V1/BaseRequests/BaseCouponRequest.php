<?php

namespace App\Http\Requests\Api\V1\BaseRequests;

use Illuminate\Foundation\Http\FormRequest;

class BaseCouponRequest extends FormRequest
{
    public function mappedAttributes(): array
    {
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

    public function messages(): array
    {
        return [
            'data.relationships.user.data.id.required' => 'Please choose the user this coupon belongs to.',
            'data.relationships.user.data.id.exists' => 'The selected coupon user does not exist.',
            'data.attributes.code.required' => 'The coupon code is required.',
            'data.attributes.code.unique' => 'This coupon code is already in use.',
            'data.attributes.code.string' => 'The coupon code must be text.',
            'data.attributes.code.max' => 'The coupon code may not be longer than 32 characters.',
            'data.attributes.code.min' => 'The coupon code must be at least 5 characters.',
            'data.attributes.code.regex' => 'The coupon code may only contain letters, numbers, and hyphens.',
            'data.attributes.discountPercentage.required' => 'The coupon discount percentage is required.',
            'data.attributes.discountPercentage.numeric' => 'The coupon discount percentage must be a valid number.',
            'data.attributes.discountPercentage.min' => 'The coupon discount percentage cannot be negative.',
            'data.attributes.discountPercentage.max' => 'The coupon discount percentage cannot be greater than 100.',
            'data.attributes.expiresAt.required' => 'The coupon expiry date is required.',
            'data.attributes.expiresAt.date' => 'The coupon expiry date must be a valid date.',
            'data.attributes.expiresAt.after' => 'The coupon expiry date must be in the future.',
            'data.attributes.maxApplicablePrice.required' => 'The maximum applicable price is required.',
            'data.attributes.maxApplicablePrice.numeric' => 'The maximum applicable price must be a valid number.',
            'data.attributes.maxApplicablePrice.min' => 'The maximum applicable price cannot be negative.',
        ];
    }
}
