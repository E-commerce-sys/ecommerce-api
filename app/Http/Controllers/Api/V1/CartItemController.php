<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends ApiController
{
    public function store(StoreCartItemRequest $request)
    {
        $mappedAttributes = $request->mappedAttributes();

        // cached cart
        $cart = $request->user()->cart;

        if ($cart->cartItems()->count() > 30) {
            return $this->error('You cannot have more than 30 products in your cart!', 400);
        }

        $extraPrice = optional(
            $request
            ->product
            ->productSizes
            ->where(
                'id', $mappedAttributes['product_size_id'] ?? null
            )
            ->first()
        )
        ->extra_price;

        CartItem::create([
            ...$mappedAttributes,
            'cart_id'    => $cart->id,
            'unit_price' => $request->product->effective_price + ($extraPrice ?? 0), // cached product
        ]);

        $cart->update([
            'total_price' => $cart->cartItems->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            })
        ]);

        return $this->success([], 'This product has been added to your cart', 201);
    }

    public function destroy(Request $request) {
        auth('sanctum')
        ->user()
        ->cart
        ->cartItems()
        ->where('product_id', $request->productId)
        ->firstOrFail()
        ->delete();
        return $this->ok([], 'Product was removed from your cart');
    }
}
