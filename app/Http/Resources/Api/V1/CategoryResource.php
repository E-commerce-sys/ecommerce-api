<?php

namespace App\Http\Resources\Api\V1;

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
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'icon' => $this->icon,
            ],
            'relationships' => [
                'parent' => [
                    'data' => $this->parent_id ? [
                        'type' => 'category',
                        'id' => $this->parent_id,
                    ] : null,
                    'links' => [
                        'self' => route('categories.show', ['category' => $this->id])
                    ]
                ],
                'children' => [
                    'data' => [
                        'type' => 'category',
                    ]
                ]
            ],
            'included' => [
                'parent' => new CategoryResource($this->whenLoaded('parent')),
            ],
            'links' => ['self' => route('categories.show', ['category' => $this->id])],
        ];
    }
}
