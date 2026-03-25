<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreWishListItemRequest;
use App\Http\Resources\Api\V1\WishListItemResource;
use App\Models\WishList;
use App\Models\WishListItem;

class WishListItemController extends Controller
{
    public function store(StoreWishListItemRequest $request) {
        $wishlist = WishList::firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        $wishlistItem = WishListItem::create(
            [
                ...$request->mappedAttributes(),
                'wish_list_id' => $wishlist->id
            ]
        );

        return new WishListItemResource($wishlistItem);

    }
}
