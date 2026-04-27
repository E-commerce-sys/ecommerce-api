<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Pest\Mutate\Mutators\Logical\BooleanAndToBooleanOr;

class CartItemController extends ApiController
{
    public $subtotal = null;
    public $shipping = null;
    public $total = null;

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
            ->productVariant
            ->size
        )->extra_price;

        $cartItem = $cart
        ->cartItems()
        ->where(
            'product_variant_id', $mappedAttributes['product_variant_id']
        )->first();

        // if cart item is already in the cart
        if ($cartItem) {
            return $this->addQuantity($cart, $cartItem, $mappedAttributes['quantity']);
        }  
        
        CartItem::create([
            ...$mappedAttributes,
            'cart_id'    => $cart->id,
            'unit_price' => $request
            ->productVariant
            ->product
            ->effective_price + ($extraPrice ?? 0), // cached product variant
        ]);

        $this->calculateTotal($cart);

        $cart->update([
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping,
            'total_price' => $this->total,
        ]);

        return $this->success([], 'Product was added to your cart', 201);
    }

    public function update(UpdateCartItemRequest $request) {
        $cartItem = $request
        ->user()
        ->cart
        ->cartItems()
        ->where(
            'id', $request->cartItemId
        )
        ->firstOrFail();

        $mappedAttributes = $request->mappedAttributes();

        if ($cartItem->quantity + $mappedAttributes['quantity']  > CartItem::$maxQuantity) {
            return $this->error('You cannot add more than ' . CartItem::$maxQuantity . ' items to your cart!', 403);
        }

        $cartItem->update($mappedAttributes);

        $this->calculateTotal($cartItem->cart);

        $cartItem->cart->update([
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping,
            'total_price' => $this->total,
        ]);

        return $this->ok(
            new CartResource($cartItem->cart),
            'Product was updated in your cart'
        );
    }

    public function destroy(Request $request) {
        $userCart = auth('sanctum')->user()->cart;
        $userCart
        ->cartItems()
        ->where('id', $request->cartItemId)
        ->firstOrFail()
        ->delete();
        
        $this->calculateTotal($userCart);

        $userCart->update([
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping,
            'total_price' => $this->total,
        ]);

        return $this->ok(new CartResource($userCart), 'Product was removed from your cart');
    }

    // helper function 
    public function getSubTotal($cart) {
        return $cart->cartItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });
    }

    // helper function
    private function calculateTotal($cart) {
        $this->subtotal = $this->getSubTotal($cart);
        $this->shipping = ($this->subtotal > Cart::$freeShippingLimit) || ($cart->cartItems->isEmpty()) ? 0 : Cart::$shippingCost;
        $this->total = $this->subtotal + $this->shipping;
    }

    private function addQuantity($cart, $cartItem, $amount) {
        if ($cartItem->quantity + $amount > CartItem::$maxQuantity) {
            return $this->error('You cannot add more than ' . CartItem::$maxQuantity . ' items to your cart!', 403);
        }

        $cartItem->update([
            'quantity' => $cartItem->quantity + $amount
        ]); 
        $this->calculateTotal($cart);
             
        $cart->update([
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping,
            'total_price' => $this->total,
        ]);

        return $this->success([], 'Quantity of this product was updated in your cart', 201);
    }
}
