<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index() {
        return ProductResource::collection(
            QueryBuilder::for(Product::class)
            ->allowedFilters(Product::allowedFilters())
            ->allowedSorts(Product::allowedSorts())
            ->allowedIncludes('category')
            ->paginate()
        );
    }
    
    public function show($product_id) {
        return new ProductResource(
            QueryBuilder::for(Product::class)
            ->allowedIncludes(Product::allowedIncludes())
            ->findOrFail($product_id)
        );
    }
}
