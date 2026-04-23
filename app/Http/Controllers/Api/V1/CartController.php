<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\CartResource;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CartController extends ApiController
{
    public function show() {
        return new CartResource(
            QueryBuilder::for(auth('sanctum')->user()->cart())
            ->allowedIncludes(Cart::allowedIncludes())
            ->firstOrFail()
        );
    }

    public function applyCoupon(Request $request) {
        $user = auth('sanctum')->user();
        $coupon = Coupon::where('code', $request->input('code'))->firstOrFail();
        $usersCart = $user->cart;
        if (is_null($usersCart)) {
            return $this->error('User cart does not exist!', 404);
        }
        if (!is_null($usersCart->coupon_id)) {
            return $this->error('Coupon already applied!', 403);
        }
        $usersCart->update([
            'coupon_id' => $coupon->id,
            'total_price' => $coupon->apply($usersCart->total_price)
        ]);
        return $this->ok(new CartResource($usersCart), 'Coupon applied successfully!');
    }
}
