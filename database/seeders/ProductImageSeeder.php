<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primaryProductImages = [];

        for ($i = 1; $i <= 14; $i++) {
            $primaryProductImages[] = 
            "
                https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/product-images/product-image{$i}.png
            ";
        }

        Product::all()->each(function ($product) use ($primaryProductImages) {
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $primaryProductImages[array_rand($primaryProductImages)],
                'is_primary' => true
            ]);
        });
    }
}
