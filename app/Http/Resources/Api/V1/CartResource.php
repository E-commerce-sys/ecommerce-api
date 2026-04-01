<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'cart',
            'id' => $this->id,
            'attributes' => [
                'totalPrice' => $this->total_price
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ]
                ],
                'cartItems' => $this->when($this->relationLoaded('cartItems'), fn() => [
                        'data' => CartItemResource::collection($this->cartItems)->map(fn($item) => [
                            'type' => 'cartItem',
                            'id'   => $item->id,
                        ]),
                ])
            ],
            'included' => [
                'cartItems' => $this->when($this->relationLoaded('cartItems'), fn() => CartItemResource::collection($this->cartItems))
            ]
        ];
    }
}
