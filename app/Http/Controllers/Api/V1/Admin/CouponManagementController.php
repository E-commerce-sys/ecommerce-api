<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CouponResource;
use App\Models\Coupon;
use Spatie\QueryBuilder\QueryBuilder;

class CouponManagementController extends Controller
{
    public function index() {
        $per_page = request('per_page', 10);
        return CouponResource::collection(
            QueryBuilder::for(Coupon::class)
            ->allowedFilters(Coupon::allowedFilters())
            ->allowedIncludes(Coupon::allowedIncludes())
            ->paginate($per_page)
        );
    }
}
