<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\StoreCouponRequest;
use App\Http\Resources\Api\V1\CouponResource;
use App\Models\Coupon;
use Spatie\QueryBuilder\QueryBuilder;

class CouponManagementController extends ApiController
{
    public function index() {
        $this->authorize('viewAny', Coupon::class);
        $per_page = request('per_page', 10);
        return CouponResource::collection(
            QueryBuilder::for(Coupon::class)
            ->allowedFilters(Coupon::allowedFilters())
            ->allowedIncludes(Coupon::allowedIncludes())
            ->paginate($per_page)
        );
    }

    public function store(StoreCouponRequest $request) {
        $this->authorize('create', Coupon::class);
        $coupon = Coupon::create(
            [
                ...$request->mappedAttributes(),
                'is_active' => true
            ]
        );
        return new CouponResource($coupon);
    }
}
