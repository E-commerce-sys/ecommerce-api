<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'banner',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'image' => $this->image,
                'link' => $this->link
            ],
            'links' => ['self' => route('categories.show', ['category' => $this->id])],
        ];
    }
}
