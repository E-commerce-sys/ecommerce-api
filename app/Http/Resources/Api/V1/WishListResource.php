<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'wishlist',
            'id' => $this->id,
            'attributes' => [],
            'relationships' => [
                'user' => [
                    'data`' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ]
                ],
                'wishListItems' => $this->whenLoaded('wishListItems', fn() => [
                    'data' => WishListItemResource::collection($this->items)->map(fn($item) => [
                        'type' => 'wishlist-item',
                        'id' => $item->id
                    ])
                ])
                ],
            'included' => [
                'user' => new UserResource($this->whenLoaded('user')),
                'wishListItems' => WishListItemResource::collection($this->whenLoaded('wishListItems')),
            ],
            'links' => ['self' => route('wishlists.show', ['wishlist' => $this->id])],
        ];
    }
}
