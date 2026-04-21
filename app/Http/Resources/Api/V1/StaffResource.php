<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\PermissionResource;
use App\Http\Resources\Api\V1\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
                'isSuperAdmin' => $this->hasRole('super_admin'),    
                'isAdmin' => $this->hasRole('admin'),
                'isBlocked' => $this->blocked_at ? true : false,
                'isVerified' => $this->email_verified_at ? true : false,
            ],
            'included' => [
                'roles' => RoleResource::collection($this->whenLoaded('roles')),
                'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            ]
        ];
    }
}
