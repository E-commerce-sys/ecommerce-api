<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Models\Address;
use App\Models\Order;

class OrderController extends ApiController
{
    // ! stock check and deduction is missing
    public function store(StoreOrderRequest $request) {
        $user = auth('sanctum')->user();
        $cart = optional($user->cart);

        if (!$cart || $cart->cartItems->isEmpty()) return $this->error('Your cart is empty!', 400);

        $isNewAddress = $request->boolean('isNewAddress');
        
        if ($isNewAddress) {
            if ($request->boolean('saveAddressToUser')) {
                $address = $user->addresses()->create(
                    $request->mappedAddressAttributes()
                );
            } else {
                $address = Address::create(
                    $request->mappedAddressAttributes()
                );
            }
        }

        $order = Order::create([
            'shipping_address_id' => $isNewAddress ? $address->id : $request->input('data.relationships.shippingAddress.data.id'),
            'user_id' => $user->id,
            'shipping_cost' => $cart->shipping_cost,
            'subtotal' => $cart->subtotal,
            'total_price' => $cart->total_price
        ]);

        foreach ($cart->cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_variant_id' => $cartItem->product_variant_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price
            ]);
        }

        $cart->delete();

        return $this->success([], 'Order placed successfully!', 201);
    }
}
