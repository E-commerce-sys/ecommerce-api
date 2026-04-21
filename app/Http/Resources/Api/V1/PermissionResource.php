<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'permission',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'guardName' => $this->guard_name,
            ],
        ];
    }
}