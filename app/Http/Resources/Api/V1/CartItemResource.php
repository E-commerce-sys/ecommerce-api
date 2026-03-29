<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'cartItem',
            'id' => $this->id,
            'attributes' => [
                'quantity' => $this->quantity,
                'unitPrice' => $this->unit_price
            ],
            'relationships' => [
                'cart' => [
                    'data' => [
                        'type' => 'cart',
                        'id' => $this->cart_id
                    ]
                ],
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ]
                ],
                'productColor' => [
                    'data' => [
                        'type' => 'productColor',
                        'id' => $this->product_color_id
                    ]
                ],
                'productSize' => [
                    'data' => [
                        'type' => 'productSize',
                        'id' => $this->product_size_id
                    ]
                ]
            ],
            'included' => []
        ];
    }
}
