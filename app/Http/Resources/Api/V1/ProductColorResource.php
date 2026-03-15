<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product-color',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'hexCode' => $this->hex_code,
                'stockQuantity' => $this->stock_quantity,
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ]
                ]
            ],
            'included' => [
                'product' => new ProductResource($this->whenLoaded('product')),
            ],
        ];
    }
}
