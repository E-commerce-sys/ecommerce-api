<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index() {
        return CategoryResource::collection(
            QueryBuilder::for(Category::class)
            ->allowedFilters(Category::allowedFilters())
            ->allowedIncludes(Category::allowedIncludes())
            ->get()
            );
    }
}
