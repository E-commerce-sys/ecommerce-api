<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends ApiController
{
    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        $query = QueryBuilder::for(Category::class)
            ->allowedFilters(Category::allowedFilters())
            ->allowedIncludes(Category::allowedIncludes())
            ->orderBy(config('app.main_order_by_field'));

        if ($user?->hasRole('super_admin') || $user?->hasRole('admin')) {
            $query->withCount(['products', 'childrenProducts']); // childrenProducts becomes children_products_count
        }

        return CategoryResource::collection($query->get());
    }

    public function show(Request $request, $category_id)
    {
        $user = $request->user('sanctum');

        $query = QueryBuilder::for(Category::class)
            ->allowedIncludes(Category::allowedIncludes());

        if ($user?->hasRole('super_admin') || $user?->hasRole('admin')) {
            $query->withCount(['products', 'childrenProducts']);
        }

        return new CategoryResource(
            $query->findOrFail($category_id)
        );
    }
}
