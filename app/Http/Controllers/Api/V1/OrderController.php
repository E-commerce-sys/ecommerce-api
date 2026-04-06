<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends ApiController
{
    public ?CartItem $outOfStockItem = null;

    public function userOrders() {
        return OrderResource::collection(
            QueryBuilder::for(auth('sanctum')->user()->orders())
            ->allowedIncludes(Order::allowedIncludes())
            ->get()
        );
    }

    public function store(StoreOrderRequest $request) : JsonResponse {
        return DB::transaction(function () use ($request) {
            $user = auth('sanctum')->user();
            $cart = $user->cart()->with([
                'cartItems.productVariant.color', 
                'cartItems.productVariant.size'
            ])->first();

            if (!$cart || $cart->cartItems->isEmpty()) return $this->error('Your cart is empty!', 400);

            if ($this->isOutOfStock($cart)) {
                return $this->error(
                    [
                       'message' =>  'The product in ' . $this->outOfStockItem->productVariant->color->name . ' (size ' . $this->outOfStockItem->productVariant->size->size_label . ' ) is out of stock.',
                       'outOfStockItemId' => $this->outOfStockItem->id,
                    ],
                    400
                );
            }


            $shippingAddressId = $this->resolveAddress($request, $user);

            $order = Order::create([
                'shipping_address_id' => $shippingAddressId,
                'user_id' => $user->id,
                'shipping_cost' => $cart->shipping_cost,
                'subtotal' => $cart->subtotal,
                'total_price' => $cart->total_price
            ]);

            foreach ($cart->cartItems as $cartItem) {
                $orderItem = $order->orderItems()->create([
                    'product_variant_id' => $cartItem->product_variant_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price
                ]);

                // deduct stock
                $orderItem->productVariant()->decrement('stock', $orderItem->quantity);
            }

            

            $cart->delete();

            return $this->success([], 'Order placed successfully!', 201);
        });

        
    }

    public function isOutOfStock($cart) {
        foreach ($cart->cartItems as $cartItem) {
            if ($cartItem->productVariant->stock < $cartItem->quantity) {
                $this->outOfStockItem = $cartItem;
                return true;
            }
        }
        return false;
    }

    private function resolveAddress($request, $user)
    {
        $isNewAddress = $request->boolean('isNewAddress');

        if (!$isNewAddress) {
            return $request->input('data.relationships.shippingAddress.data.id');
        }

        if ($request->boolean('saveAddressToUser')) {
            return $user->addresses()->create(
                $request->mappedAddressAttributes()
            )->id;
        }

        return Address::create(
            $request->mappedAddressAttributes()
        )->id;
    }
}
