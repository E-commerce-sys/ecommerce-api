<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order',
            'id' => $this->id,
            'attributes' => [
                'status' => $this->status,
                'discountPercentage' => $this->discount_percentage,
                'shippingCost' => $this->shipping_cost,
                'subtotal' => $this->subtotal,
                'totalPrice' => $this->total_price
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ]
                ],
                'shippingAddress' => [
                    'data' => [
                        'type' => 'address',
                        'id' => $this->shipping_address_id
                    ]
                ],
                'orderItems' => $this->when($this->relationLoaded('orderItems'), fn() => [
                        'data' => OrderItemResource::collection($this->orderItems)->map(fn($orderItem) => [
                            'type' => 'order-item',
                            'id'   => $orderItem->id,
                        ]),
                ])
            ],
            'included' => [
                'OrderItems' => $this->when($this->relationLoaded('orderItems'), fn() => OrderItemResource::collection($this->orderItems))
            ]
        ];
    }
}
