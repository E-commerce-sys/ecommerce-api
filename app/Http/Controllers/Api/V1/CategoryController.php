<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends ApiController
{
    public function index()
    {
        return CategoryResource::collection(
            QueryBuilder::for(Category::class)
                ->allowedFilters(Category::allowedFilters())
                ->allowedIncludes(Category::allowedIncludes())
                ->orderBy(config('app.main_order_by_field'))
                ->get()
        );
    }

    public function show($category_id)
    {
        return new CategoryResource(
            QueryBuilder::for(Category::class)
                ->allowedIncludes(Category::allowedIncludes())
                ->findOrFail($category_id)
        );
    }
}
