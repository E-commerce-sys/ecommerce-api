<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseRequests\BaseAddressRequest;

class StoreAddressRequest extends BaseAddressRequest
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
            'data.attributes.addressName' => ['sometimes', 'string', 'max:32', 'min:3'],
            'data.attributes.streetName' => ['required', 'string', 'max:132', 'min:3',],
            'data.attributes.houseNumber' => ['sometimes', 'string', 'max:32', 'min:3'],
            'data.attributes.city' => ['required', 'string', 'max:32', 'min:3'],
            'data.attributes.state' => ['sometimes', 'string', 'max:32', 'min:3'],
            'data.attributes.zipCode' => ['sometimes', 'string', 'max:32', 'min:3'],
            'data.attributes.country' => ['sometimes', 'string', 'max:32', 'min:3'],
        ];
    }
}
