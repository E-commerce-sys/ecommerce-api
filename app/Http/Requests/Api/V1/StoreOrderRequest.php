<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseOrderRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends BaseOrderRequest 
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
            'data.relationships.shippingAddress.data.id' => [
                Rule::requiredIf(!$this->boolean('isNewAddress')),
                Rule::prohibitedIf($this->boolean('isNewAddress')),
                'exists:addresses,id',
            ],

            'data.included.address.attributes.addressName' => [
                Rule::requiredIf($this->boolean('isNewAddress')),
                Rule::prohibitedIf(!$this->boolean('isNewAddress')),
                'string',
                'max:32',
                'min:3',
            ],

            'data.included.address.attributes.city' => [
                Rule::requiredIf($this->boolean('isNewAddress')),
                Rule::prohibitedIf(!$this->boolean('isNewAddress')),
                'string',
                'max:32',
                'min:3',
                'alpha'
            ],
            'data.included.address.attributes.streetName' => [
                Rule::requiredIf($this->boolean('isNewAddress')),
                Rule::prohibitedIf(!$this->boolean('isNewAddress')),
                'string', 
                'max:132', 
                'min:3', 
            ],
            'data.included.address.attributes.houseNumber' => [
                'sometimes',
                'string',
                'alpha_num'
            ]
        ];
    }
}
