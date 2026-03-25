<?php

namespace App\Http\Resources\Api\V1;

use App\Models\ProductImage;
use App\Models\WishListItem;
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
                'primaryImage' => $this->images->where('is_primary', true)->first()->image,
                'effectivePrice' => $this->effective_price,
                'originalPrice' => $this->price,
                'stockQuantity' => $this->stock_quantity,
                'isBestSelling' => $this->is_best_selling,
                'isFeatured' => $this->is_featured,
                'isNewArrival' => $this->is_new_arrival,
                'isNew' => $this->is_new,
                'newArrivalImage' => $this->new_arrival_image,
                'hasDiscount' => $this->has_discount,
                'discountPercentage' => $this->discount_percentage,
                'averageRating' => $this->average_rating,
                'ratingCount' => $this->rating_count,
                'isInWishList' => auth('sanctum')->check()
                    ? $this->wishListItems
                        ->where('wish_list_id', auth('sanctum')->user()->wish_list->id)
                        ->isNotEmpty()
                    : false,
            ],
            'relationships' => [
                'category' => [
                    'data' => [
                        'type' => 'category',
                        'id' => $this->category_id,
                    ],
                    'links' => [
                        'related' => route('categories.show', ['category' => $this->category_id])
                    ]
                ],
                'productColors' => $this->when($this->relationLoaded('productColors'), fn() => [
                        'data' => ProductColorResource::collection($this->productColors)->map(fn($color) => [
                                'type' => 'product-color',
                                'id'   => $color->id,
                            ]),
                    ]
                ),
            ],
            'included' => [
                'category'      => new CategoryResource($this->whenLoaded('category')),
                'productColors' => ProductColorResource::collection($this->whenLoaded('productColors')),
            ],
            'links' => ['self' => route('products.show', ['product' => $this->id])],
        ];
    }
}
