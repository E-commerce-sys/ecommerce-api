<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order-item',
            'id' => $this->id,
            'attributes' => [
                'quantity' => $this->quantity,
                'unitPrice' => $this->unit_price
            ],
            'relationships' => [
                'order' => [
                    'data' => [
                        'type' => 'order',
                        'id' => $this->order_id
                    ]
                ],
                'productVariant' => [
                    'data' => [
                        'type' => 'product-variant',
                        'id' => $this->product_variant_id
                    ]
                ],
            ],
            'included' => [
                'productVariant' => $this->when($this->relationLoaded('productVariant'), fn() => new ProductVariantResource($this->productVariant))
            ]
        ];
    }
}
