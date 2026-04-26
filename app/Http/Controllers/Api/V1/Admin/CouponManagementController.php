<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\StoreCouponRequest;
use App\Http\Resources\Api\V1\CouponResource;
use App\Mail\CouponCreated;
use App\Models\Coupon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;

class CouponManagementController extends ApiController
{
    public function index() : AnonymousResourceCollection {
        $this->authorize('viewAny', Coupon::class);
        $per_page = request('per_page', 10);
        return CouponResource::collection(
            QueryBuilder::for(Coupon::class)
            ->allowedFilters(Coupon::allowedFilters())
            ->allowedIncludes(Coupon::allowedIncludes())
            ->orderBy(config('app.main_order_by_field'), 'desc')
            ->paginate($per_page)
        );
    }

    public function store(StoreCouponRequest $request) {
        $this->authorize('create', Coupon::class);
        $coupon = Coupon::create($request->mappedAttributes());

        $user = $coupon->user;
        Mail::to($user->email)->queue(new CouponCreated($coupon));
        
        return new CouponResource($coupon);
    }
}
