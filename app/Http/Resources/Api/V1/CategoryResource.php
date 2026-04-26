<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user('sanctum');
        
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'nameEn' => $this->name_en,
                'nameAr' => $this->name_ar,
                'nameKu' => $this->name_ku,
                'icon' => $this->icon,
                
                'numberOfProducts' => $this->when(
                    $user?->hasRole('super_admin') || $user?->hasRole('admin'),
                    fn () => $this->parent_id
                        ? $this->products_count          // child category
                        : $this->children_products_count // parent category
                ),

                'isParent' => $this->parent_id ? false : true
            ],
            'relationships' => [
                'parent' => [
                    'data' => [
                        'type' => 'category',
                        'id' => $this->parent_id,
                    ],
                ],
                'children' => $this->when($this->relationLoaded('children'), fn() => [
                        'data' => CategoryResource::collection($this->children)->map(fn($child) => [
                                'type' => 'category',
                                'id'   => $child->id,
                            ]),
                    ]
                ),
            ],
            'included' => [
                'parent' => new CategoryResource($this->whenLoaded('parent')),
                'children' => CategoryResource::collection($this->whenLoaded('children')),
            ],
            'links' => ['self' => route('categories.show', ['category' => $this->id])],
        ];
    }
}
