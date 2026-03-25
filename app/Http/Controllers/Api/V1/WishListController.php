<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WishListResource;
use App\Models\WishList;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class WishListController extends Controller
{
    public function show(Request $request) {
        $wishlist = $request->user()->wish_list();
        return new WishListResource(
            QueryBuilder::for($wishlist)
            ->allowedIncludes(WishList::allowedIncludes())
            ->firstOrFail()
        );
    }
}   
