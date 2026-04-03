<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product-variant',
            'id' => $this->id,
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ]                    
                ],
                'color' => [
                    'data' => [
                        'type' => 'product-color',
                        'id' => $this->color_id
                    ]
                ],
                'size' => [
                    'data' => [
                        'type' => 'product-size',
                        'id' => $this->size_id
                    ]
                ]
            ],
            'included' => [
                'product' => $this->when($this->relationLoaded('product'), fn() => new ProductResource($this->product)),
                'color' => $this->when($this->relationLoaded('color'), fn() => new ProductColorResource($this->color)),
                'size' => $this->when($this->relationLoaded('size'), fn() => new ProductSizeResource($this->size))
            ]
        ];
    }
}
