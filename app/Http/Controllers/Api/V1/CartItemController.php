<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends ApiController
{
    public function store(StoreCartItemRequest $request) {
        $user = auth('sanctum')->user();
        $mappedAttributes = $request->mappedAttributes();

        $cart = $user->cart()->firstOrCreate([]);

        if ($cart->cartItems()->count() > 30) {
            return $this->error('You cannot have more than 30 products in your cart!', 400);
        }

        $cartItem = CartItem::create([
            ...$mappedAttributes,
            'cart_id' => $cart->id,
            'unit_price' => Product::findOrFail($mappedAttributes['product_id'])->effective_price
        ]);

        $cart->update([
            'total_price' => $cart->cartItems->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            })
        ]);

        return new CartItemResource($cartItem);
    }
}
