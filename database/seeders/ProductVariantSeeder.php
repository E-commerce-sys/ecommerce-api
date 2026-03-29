<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with(['productColors', 'productSizes'])->get();

        foreach ($products as $product) {

            $colors = $product->productColors;
            $sizes  = $product->productSizes;

            // CASE 1: Has both colors and sizes
            if ($colors->isNotEmpty() && $sizes->isNotEmpty()) {
                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color_id'   => $color->id,
                            'size_id'    => $size->id,
                            'stock'      => rand(0, 20),
                        ]);
                    }
                }
            }

            // CASE 2: Only colors
            elseif ($colors->isNotEmpty()) {
                foreach ($colors as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id'   => $color->id,
                        'size_id'    => null,
                        'stock'      => rand(0, 20),
                    ]);
                }
            }

            // CASE 3: Only sizes
            elseif ($sizes->isNotEmpty()) {
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id'   => null,
                        'size_id'    => $size->id,
                        'stock'      => rand(0, 20),
                    ]);
                }
            }
        }
    }
}