<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'address',
            'id' => $this->id,
            'attributes' => [
                'addressName' => $this->address_name,
                'houseNumber' => $this->house_number,
                'streetName' => $this->street_name,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zipCode' => $this->zip_code
            ]
        ];
    }
}
