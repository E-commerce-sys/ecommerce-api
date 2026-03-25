<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'wishListItem',
            'id' => $this->id,
            'attributes' => [
                
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ]
                ],
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ]
                ]
            ],
            'included' => [
                'product' => new ProductResource($this->whenLoaded('product')),
                'user' => new UserResource($this->whenLoaded('user')),
            ]
        ];
    }
}
