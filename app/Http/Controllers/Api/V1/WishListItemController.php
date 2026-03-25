<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWishListItemRequest;
use App\Http\Resources\Api\V1\WishListItemResource;
use App\Models\WishListItem;
use Spatie\QueryBuilder\QueryBuilder;

class WishListItemController extends Controller
{
    public function usersWishListItems() {
        $items = auth('sanctum')->user()->wishListItems();
        return WishListItemResource::collection(
            QueryBuilder::for($items)
            ->allowedIncludes(WishListItem::allowedIncludes())
            ->get()
        );
    }

    public function store(StoreWishListItemRequest $request) {
        $wishlistItem = WishListItem::create([
            ...$request->mappedAttributes(),
            'user_id' => $request->user()->id
        ]);

        return new WishListItemResource($wishlistItem);

    }
}
