<?php

namespace App\Http\Resources\Api\V1;

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
        $numberOfProducts = $this->parent_id
            ? $this->products()->count()
            : Product::whereIn(
                'category_id',
                $this->children()->select('id')
            )->count();
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'nameEn' => $this->name_en,
                'nameAr' => $this->name_ar,
                'nameKu' => $this->name_ku,
                'icon' => $this->icon,
                'NumberOfProducts' => $numberOfProducts,
                'isParent' => $this->parent_id ? false : true
            ],
            'relationships' => [
                'parent',
                'children',
            ],
            'included' => [
                'parent' => new CategoryResource($this->whenLoaded('parent')),
                'children' => CategoryResource::collection($this->whenLoaded('children')),
            ],
            'links' => ['self' => route('categories.show', ['category' => $this->id])],
        ];
    }
}
