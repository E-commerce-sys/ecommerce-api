<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Models\CartItem;

class CartItemController extends ApiController
{
    public function store(StoreCartItemRequest $request)
    {
        $mappedAttributes = $request->mappedAttributes();

        $cart = $request->user()->cart;

        if ($cart->cartItems()->count() > 30) {
            return $this->error('You cannot have more than 30 products in your cart!', 400);
        }

        $cartItem = CartItem::create([
            ...$mappedAttributes,
            'cart_id'    => $cart->id,
            'unit_price' => $request->product->effective_price, // used cached product
        ]);

        // Use a DB aggregate instead of loading all cart item models into memory
        $cart->update([
            'total_price' => $cart->cartItems->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            })
        ]);

        return new CartItemResource($cartItem);
    }
}
