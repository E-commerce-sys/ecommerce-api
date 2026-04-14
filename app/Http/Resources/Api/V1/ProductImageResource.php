<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product-image',
            'id' => $this->id,
            'attributes' => [
                'image' => $this->image,
                'isPrimary' => $this->is_primary,
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id,
                    ],
                ],
            ],
        ];
    }
}
