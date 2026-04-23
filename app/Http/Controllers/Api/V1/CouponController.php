<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    public function applyCoupon(Request $request) {
        $user = auth('sanctum')->user();
        $coupon = Coupon::findOrFail('code', $request->input('code'));
        $usersCart = $user->cart;
        $usersCart->update([
            'coupon_id' => $coupon->id,
            'total_price' => $coupon->apply($usersCart->total_price)
        ]);
        return $this->ok(new CartResource($usersCart), 'Coupon applied successfully!');
    }
}
