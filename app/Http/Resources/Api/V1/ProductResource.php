<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'nameEn' => $this->name_en,
                'nameAr' => $this->name_ar,
                'nameKu' => $this->name_ku,
                'descriptionEn' => $this->description_en,
                'descriptionAr' => $this->description_ar,
                'descriptionKu' => $this->description_ku,
                'price' => $this->price,
                'isBestSelling' => $this->is_best_selling,
                'isFeatured' => $this->is_featured,
                'isNewArrival' => $this->is_new_arrival,
                'newArrivalImage' => $this->new_arrival_image,
                'discountPercentage' => $this->discount_percentage,
                'averageRating' => $this->average_rating,
                'ratingCount' => $this->rating_count,
            ],
            'relationships' => [
                'category' => [
                    'data' => [
                        'type' => 'category',
                        'id' => $this->category_id,
                    ],
                    'links' => [
                        'self' => 'todo'
                    ]
                ]
            ],
            'included' => [
                'category' => new CategoryResource($this->whenLoaded('category')),
            ],
            'links' => ['self' => route('products.show', ['product' => $this->id])],
        ];
    }
}
