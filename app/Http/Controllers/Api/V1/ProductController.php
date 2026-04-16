<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplaceProductRequest;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends ApiController
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
        $this->authorize('create', Product::class);
        return DB::transaction(function () use($request) {
            $mappedAttributes = $request->mappedAttributes();
            if ($request->hasFile('data.attributes.newArrivalImage')) {
                $path = $request->file('data.attributes.newArrivalImage')->store('all-images/product-images/is-new-arrival-images', 's3');
                $mappedAttributes['new_arrival_image'] = Storage::disk('s3')->url($path);
            }
            $product = Product::create($mappedAttributes);

            // storing images
            $this->storeProductImages($product, $request->mappedImages());

            // storing variants
            $this->storeProductVariants($product, $request->mappedVariants());

            return new ProductResource($product);
        });
    }

    public function replace(ReplaceProductRequest $request, $product_id) {
        $product = Product::findOrFail($product_id);
        $this->authorize('replace', $product);

        return DB::transaction(function () use ($request, $product) {
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

            // Wipe and replace images
            if ($request->has('data.included.images')) {
                // S3 deletion logic
                foreach ($product->images as $imageModel) {
                    if ($imageModel->image) {
                        $oldPath = str_replace(Storage::disk('s3')->url(''), '', $imageModel->image);
                        Storage::disk('s3')->delete($oldPath);
                    }
                }
                $product->images()->delete(); 
                $this->storeProductImages($product, $request->mappedImages());
            }

            // Wipe and replace variants
            if ($request->has('data.included.variants')) {
                $product->variants()->delete();
                $this->storeProductVariants($product, $request->mappedVariants());
            }

            return new ProductResource($product->fresh());
        });
    }

    public function destroy($product_id) {
        $product = Product::findOrFail($product_id);
        $this->authorize('delete', $product);
        // S3 deletion logic
        $newArrivalImagePath = str_replace(Storage::disk('s3')->url(''), '', $product->new_arrival_image);
        Storage::disk('s3')->delete($newArrivalImagePath);
        foreach ($product->images as $imageModel) {
            if ($imageModel->image) {
                $oldPath = str_replace(Storage::disk('s3')->url(''), '', $imageModel->image);
                Storage::disk('s3')->delete($oldPath);
            }
        }
        $product->delete();
        return $this->ok([], 'Product deleted successfully!');
    }

    public function getSimilarProducts(Request $request) {
        $categoryIds = $request->query('categoryIds', '');
        return ProductResource::collection(
            Product::getSimilarProducts($categoryIds)
        );
    }


    // helper function
    protected function storeProductImages($product, $images) {
        if ($images) {
            foreach ($images as $image) {
                $path = $image['image']->store('all-images/product-images', 's3');
                $product->images()->create([
                    'image' => Storage::disk('s3')->url($path),
                    'is_primary' => $image['is_primary']
                ]);
            }
        }
    }

    // helper function
    protected function storeProductVariants($product, $variants) {
        if ($variants) {
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
                    'size_id' => $size?->id
                ]);
            }
        }
    }
}
