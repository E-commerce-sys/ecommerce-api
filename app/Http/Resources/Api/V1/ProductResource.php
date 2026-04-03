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
                'primaryImage' => optional($this->images->firstWhere('is_primary', true))->image,
                'effectivePrice' => $this->effective_price,
                'originalPrice' => $this->price,
                'isBestSelling' => $this->is_best_selling,
                'isFeatured' => $this->is_featured,
                'isNewArrival' => $this->is_new_arrival,
                'isNew' => $this->is_new,
                'newArrivalImage' => $this->new_arrival_image,
                'hasDiscount' => $this->has_discount,
                'discountPercentage' => $this->discount_percentage,
                'hasSize' => $this->has_size,
                'hasColor' => $this->has_color,
                'averageRating' => $this->average_rating,
                'ratingCount' => $this->rating_count,
                'isInWishList' => $this->when($request->routeIs('products.index'), $this->is_in_wish_list)
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
                'variants' => $this->when($this->relationLoaded('variants'), fn() => [
                        'data' => ProductVariantResource::collection($this->variants)->map(fn($variant) => [
                                'type' => 'product-variant',
                                'id'   => $variant->id,
                            ]),
                    ]
                ),
                'productSizes' => $this->when($this->relationLoaded('productSizes'), fn() => [
                        'data' => ProductSizeResource::collection($this->productSizes)->map(fn($size) => [
                                'type' => 'product-size',
                                'id'   => $size->id,
                            ]),
                    ]
                ),
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
                'variants'      => ProductVariantResource::collection($this->whenLoaded('variants')),
                'productColors' => ProductColorResource::collection($this->whenLoaded('productColors')),
                'productSizes' => ProductSizeResource::collection($this->whenLoaded('productSizes')),
            ],
            'links' => ['self' => route('products.show', ['product' => $this->id])],
        ];
    }
}
