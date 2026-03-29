<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends ApiController
{
    public function store(StoreCartItemRequest $request) {
        $user = auth('sanctum')->user();
        $mappedAttributes = $request->mappedAttributes();

        if ($user->cartItems()->count() > 30) {
            return $this->error('You cannot have more than 30 products in your wish list!', 400);
        }

        $cartItem = CartItem::create([
            ...$mappedAttributes,
            'user_id' => $user->id,
            'cart_id' => $user->cart->id,
            'unit_price' => Product::findOrFail($mappedAttributes['product_id'])->price
        ]);

        return new CartItemResource($cartItem);
    }
}
