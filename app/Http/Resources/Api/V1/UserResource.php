<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\V1\ResearchResource;
use App\States\Arrived;
use App\States\Cancelled;
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
                $this->mergeWhen($request->user()?->hasRole('super_admin') || $request->user()->hasRole('admin'), [
                    'isSuperAdmin' => $this->hasRole('super_admin'),    
                    'isAdmin' => $this->hasRole('admin'),
                    $this->mergeWhen($request->routeIs('users.index'), [
                        'isBlocked' => $this->blocked_at ? true : false,
                        'isVerified' => $this->email_verified_at ? true : false,
                        'numOfOrders' => $this->orders->count(),
                        'numCancelledOrders' => $this->orders->where('status', Cancelled::$name)->count(),
                        'numArrivedOrders' => $this->orders->where('status', Arrived::$name)->count(),
                    ])
                ]),
                
            ],
        ];
    }
}
