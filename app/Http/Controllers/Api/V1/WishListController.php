<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WishListResource;
use App\Models\WishList;
use Spatie\QueryBuilder\QueryBuilder;

class WishListController extends Controller
{
    public function show($wishlist_id) {
        return new WishListResource(
            QueryBuilder::for(WishList::class)
            ->findOrFail($wishlist_id)
        );
    }
}
