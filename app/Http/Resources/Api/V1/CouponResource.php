<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'coupon',
            'id' => $this->id,
            'attributes' => [
                'code' => $this->code,
                'discountPercentage' => $this->discount_percentage,
                'isActive' => $this->is_active,
                'expiresAt' => $this->expires_at,
                'maxApplicablePrice' => $this->max_applicable_price
            ],
            'included' => [
                'user' => new UserResource($this->whenLoaded('user')),
            ]
        ];
    }
}
