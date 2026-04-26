<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreWishListItemRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Resources\Api\V1\WishListItemResource;
use App\Models\WishListItem;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class WishListItemController extends ApiController
{
    public function usersWishListItems() {
        $items = auth('sanctum')->user()->wishListItems();
        return WishListItemResource::collection(
            QueryBuilder::for($items)
            ->allowedIncludes(WishListItem::allowedIncludes())
            ->orderBy(config('app.main_order_by_field'), 'desc')
            ->paginate(8)
        );
    }

    public function store(StoreWishListItemRequest $request) {
        $user = auth('sanctum')->user();

        if ($user->wishListItems()->count() > 56) {
            return $this->error('You cannot have more than 50 products in your wish list!', 400);
        }

        $wishlistItem = WishListItem::create([
            ...$request->mappedAttributes(),
            'user_id' => $request->user()->id
        ]);

        return new WishListItemResource($wishlistItem);
    }

    public function destroy(Request $request) {
        auth('sanctum')
        ->user()
        ->wishListItems()
        ->where('product_id', $request->productId)
        ->firstOrFail()
        ->delete();
        return $this->ok([], 'Wish list item removed successfully!');
    }
}
