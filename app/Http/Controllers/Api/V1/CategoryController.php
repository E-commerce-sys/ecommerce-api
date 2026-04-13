<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends ApiController
{
    public function index() {
        return CategoryResource::collection(
            QueryBuilder::for(Category::class)
            ->allowedFilters(Category::allowedFilters())
            ->allowedIncludes(Category::allowedIncludes())
            ->get()
            );
    }

    public function show($category_id) {
        return new CategoryResource(
            QueryBuilder::for(Category::class)
            ->allowedIncludes(Category::allowedIncludes())
            ->findOrFail($category_id)
        );
    }

    public function store(StoreCategoryRequest $request) {
        $gg = Storage::disk('s3')->put('test.txt', 'hello');
        dd($gg);
        $this->authorize('create', Category::class);
        $path = $request->file('data.attributes.icon')->store('categories', 's3');
        $category = Category::create($request->mappedAttributes());
        return new CategoryResource($category);
    }
}
