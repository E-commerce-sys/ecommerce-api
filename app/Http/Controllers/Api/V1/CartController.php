<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\Cart;
use Spatie\QueryBuilder\QueryBuilder;

class CartController extends Controller
{
    public function show() {
        return new CartResource(
            QueryBuilder::for(auth('sanctum')->user()->cart())
            ->allowedIncludes(Cart::allowedIncludes())
            ->firstOrFail()
        );
    }
}
