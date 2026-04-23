<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\ReplaceProductRequest;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends ApiController
{
    public function store(StoreProductRequest $request): ProductResource
    {
        $this->authorize('create', Product::class);

        return DB::transaction(function () use ($request): ProductResource {
            $mappedAttributes = $request->mappedAttributes();

            if ($request->hasFile('data.attributes.newArrivalImage')) {
                $path = $request->file('data.attributes.newArrivalImage')->store('all-images/product-images/is-new-arrival-images', 's3');
                $mappedAttributes['new_arrival_image'] = Storage::disk('s3')->url($path);
            }

            $product = Product::create($mappedAttributes);

            $this->storeProductImages($product, $request->mappedImages());
            $this->storeProductVariants($product, $request->mappedVariants());

            return new ProductResource($product);
        });
    }

    public function replace(ReplaceProductRequest $request, int $productId): ProductResource
    {
        $product = Product::findOrFail($productId);

        $this->authorize('replace', $product);

        return DB::transaction(function () use ($request, $product): ProductResource {
            $mappedAttributes = $request->mappedAttributes();

            if ($request->hasFile('data.attributes.newArrivalImage')) {
                if ($product->new_arrival_image) {
                    $oldPath = str_replace(Storage::disk('s3')->url(''), '', $product->new_arrival_image);
                    Storage::disk('s3')->delete($oldPath);
                }

                $path = $request->file('data.attributes.newArrivalImage')->store('all-images/product-images/is-new-arrival-images', 's3');
                $mappedAttributes['new_arrival_image'] = Storage::disk('s3')->url($path);
            }

            $product->update($mappedAttributes);

            if ($request->has('data.included.images')) {
                $this->deleteProductImagesFromStorage($product);
                $product->images()->delete();
                $this->storeProductImages($product, $request->mappedImages());
            }

            if ($request->has('data.included.variants')) {
                $product->variants()->delete();
                $this->storeProductVariants($product, $request->mappedVariants());
            }

            return new ProductResource($product->fresh());
        });
    }

    public function update(UpdateProductRequest $request, int $productId): ProductResource
    {
        $product = Product::findOrFail($productId);

        $this->authorize('update', $product);

        $product->update($request->mappedAttributes());

        return new ProductResource($product);
    }

    public function destroy(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        $this->authorize('delete', $product);

        $newArrivalImagePath = str_replace(Storage::disk('s3')->url(''), '', $product->new_arrival_image);
        Storage::disk('s3')->delete($newArrivalImagePath);
        $this->deleteProductImagesFromStorage($product);

        $product->delete();

        return $this->ok([], 'Product deleted successfully!');
    }

    /**
     * @param  array<int, array{image: \Illuminate\Http\UploadedFile|null, is_primary: bool}>  $images
     */
    protected function storeProductImages(Product $product, array $images): void
    {
        foreach ($images as $image) {
            $path = $image['image']->store('all-images/product-images', 's3');

            $product->images()->create([
                'image' => Storage::disk('s3')->url($path),
                'is_primary' => $image['is_primary'],
            ]);
        }
    }

    /**
     * @param  array<int, array{stock: mixed, color: array{hex_code: mixed}|null, size: array{size_label: mixed, extra_price: mixed}|null}>  $variants
     */
    protected function storeProductVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {
            $color = $variant['color'] ? $product->productColors()->create([
                'hex_code' => $variant['color']['hex_code'],
            ]) : null;

            $size = $variant['size'] ? $product->productSizes()->create([
                'size_label' => $variant['size']['size_label'],
                'extra_price' => $variant['size']['extra_price'],
            ]) : null;

            $product->variants()->create([
                'stock' => $variant['stock'],
                'color_id' => $color?->id,
                'size_id' => $size?->id,
            ]);
        }
    }

    protected function deleteProductImagesFromStorage(Product $product): void
    {
        foreach ($product->images as $imageModel) {
            if ($imageModel->image) {
                $oldPath = str_replace(Storage::disk('s3')->url(''), '', $imageModel->image);
                Storage::disk('s3')->delete($oldPath);
            }
        }
    }
}
