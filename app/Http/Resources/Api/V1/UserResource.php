<?php

namespace App\Http\Resources\Api\V1;

use App\States\Arrived;
use App\States\Cancelled;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'firstName' => $this->first_name,
                'lastName' => $this->last_name,
                'email' => $this->email,

                $this->mergeWhen(
                    $request->user()?->hasRole('super_admin') || $request->user()?->hasRole('admin'),
                    function () use ($request) {
                        return [
                            'isSuperAdmin' => $this->hasRole('super_admin'),
                            'isAdmin' => $this->hasRole('admin'),

                            $this->mergeWhen(
                                $request->routeIs('admin.users.index'),
                                function () {
                                    return [
                                        'isBlocked' => (bool) $this->blocked_at,
                                        'isVerified' => (bool) $this->email_verified_at,
                                        'numOfOrders' => $this->orders->count(),
                                        'numCancelledOrders' => $this->orders->where('status', Cancelled::$name)->count(),
                                        'numArrivedOrders' => $this->orders->where('status', Arrived::$name)->count(),
                                    ];
                                }
                            ),
                        ];
                    }
                ),
            ],
            'included' => [
                'mainAddress' => new AddressResource($this->mainAddress),
            ],
        ];
    }
}