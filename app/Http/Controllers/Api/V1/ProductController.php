<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index() {
        return ProductResource::collection(
            QueryBuilder::for(Product::class)
            ->allowedFilters(Product::allowedFilters())
            ->allowedSorts(Product::allowedSorts())
            ->allowedIncludes(Product::allowedIncludes())
            ->with(['images'])
            ->withExists(
                [
                    'wishListItems as is_in_wish_list' => function ($query) {
                        $query->where('user_id', auth('sanctum')->id());
                    }
                ]
            )
            ->paginate(40)
        );
    }
    
    public function show($product_id) {
        return new ProductResource(
            QueryBuilder::for(Product::class)
            ->allowedIncludes(Product::allowedIncludes())
            ->findOrFail($product_id)
        );
    }

    public function store(StoreProductRequest $request) {
        
    }

    public function getSimilarProducts(Request $request) {
        $categoryIds = $request->query('categoryIds', '');
        return ProductResource::collection(
            Product::getSimilarProducts($categoryIds)
        );
    }
}
