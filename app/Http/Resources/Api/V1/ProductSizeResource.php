<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product-size',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'sizeLabel' => $this->size_label,
                'extraPrice' => $this->extra_price
            ],
        ];
    }
}
