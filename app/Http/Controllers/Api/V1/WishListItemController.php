<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreWishListItemRequest;
use App\Http\Resources\Api\V1\WishListItemResource;
use App\Models\WishListItem;
use Spatie\QueryBuilder\QueryBuilder;

class WishListItemController extends ApiController
{
    public function usersWishListItems() {
        $items = auth('sanctum')->user()->wishListItems();
        return WishListItemResource::collection(
            QueryBuilder::for($items)
            ->allowedIncludes(WishListItem::allowedIncludes())
            ->with(['product.images'])
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

    public function destroy($wishListItem_id) {
        $wishListItem = auth('sanctum')->user()->wishListItems()->where('id', $wishListItem_id)->firstOrFail();
        $wishListItem->delete();
        return $this->ok([], 'Wish list item removed successfully!');
    }
}
