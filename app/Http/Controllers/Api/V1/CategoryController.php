<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
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
        $this->authorize('create', Category::class);
        $mappedAttributes = $request->mappedAttributes();
        $iconFile = $request->file('data.attributes.icon');
        if ($iconFile) {
            $path = $iconFile->store('all-images/subcategory-icons', 's3');
            $mappedAttributes['icon'] = Storage::disk('s3')->url($path);
        }
        $category = Category::create($mappedAttributes);
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, $category_id) {
        $category = Category::findOrFail($category_id);
        $this->authorize('update', $category);
        $mappedAttributes = $request->mappedAttributes();
        $iconFile = $request->file('data.attributes.icon');
        if ($iconFile) {
            $path = $iconFile->store('all-images/subcategory-icons', 's3');
            $mappedAttributes['icon'] = Storage::disk('s3')->url($path);
        }
        $category->update($mappedAttributes);
        return new CategoryResource($category->fresh());
    }

    public function destroy($category_id) {
        $category = Category::findOrFail($category_id);
        $this->authorize('delete', $category);
        $category->delete();
        return $this->ok([], 'Category deleted successfully!');
    }
}
