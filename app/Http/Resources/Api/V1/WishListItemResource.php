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
                $this->whenLoaded('product', fn() => $this->product),
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ]
                ],
            ],
            'links' => ['self' => route('wish-list-items.show', ['wish_list_item' => $this->id])],
        ];
    }
}
