<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CategoryManagementController extends ApiController
{
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $this->authorize('create', Category::class);

        $category = Category::create($this->mappedAttributesWithIcon($request));

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, int $categoryId): CategoryResource
    {
        $category = Category::findOrFail($categoryId);

        $this->authorize('update', $category);

        $category->update($this->mappedAttributesWithIcon($request));

        return new CategoryResource($category->fresh());
    }

    public function destroy(int $categoryId): JsonResponse
    {
        $category = Category::findOrFail($categoryId);

        $this->authorize('delete', $category);

        $category->delete();

        return $this->ok([], 'Category deleted successfully!');
    }

    /**
     * @return array<string, mixed>
     */
    private function mappedAttributesWithIcon(StoreCategoryRequest|UpdateCategoryRequest $request): array
    {
        $mappedAttributes = $request->mappedAttributes();
        $iconFile = $request->file('data.attributes.icon');

        if ($iconFile) {
            $path = $iconFile->store('all-images/subcategory-icons', 's3');
            $mappedAttributes['icon'] = Storage::disk('s3')->url($path);
        }

        return $mappedAttributes;
    }
}
