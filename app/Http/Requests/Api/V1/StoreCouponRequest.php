<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseCouponRequest;

class StoreCouponRequest extends BaseCouponRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.relationships.user.data.id' => ['required', 'exists:users,id'],
            'data.attributes.code' => ['required', 'unique:coupons,code', 'string', 'max:32', 'min:5', 'regex:/^[A-Z0-9-]+$/i'],
            'data.attributes.discountPercentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'data.attributes.expiresAt' => ['required', 'date', 'after:now'],
            'data.attributes.maxApplicablePrice' => ['required', 'numeric', 'min:0'],
        ];
    }
}
