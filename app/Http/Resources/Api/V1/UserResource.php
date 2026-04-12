<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\V1\ResearchResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'firstName' => $this->first_name,
                'lastName' => $this->last_name,
                'email' => $this->email,
                'address' => $this->address,
                'isSuperAdmin' => $this->hasRole('super_admin'),    
                'isAdmin' => $this->hasRole('admin'),
            ],
        ];
    }
}
