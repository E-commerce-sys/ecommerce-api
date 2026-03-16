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
        $primaryProductImages = [
            'http://51.21.2.193/storage/product-images/product-image1.png',
            'http://51.21.2.193/storage/product-images/product-image2.png',
            'http://51.21.2.193/storage/product-images/product-image3.png',
            'http://51.21.2.193/storage/product-images/product-image4.png',
            'http://51.21.2.193/storage/product-images/product-image5.png',
            'http://51.21.2.193/storage/product-images/product-image6.png',
            'http://51.21.2.193/storage/product-images/product-image7.png',
            'http://51.21.2.193/storage/product-images/product-image8.png',
            'http://51.21.2.193/storage/product-images/product-image9.png',
            'http://51.21.2.193/storage/product-images/product-image10.png',
            'http://51.21.2.193/storage/product-images/product-image11.png',
            'http://51.21.2.193/storage/product-images/product-image12.png',
            'http://51.21.2.193/storage/product-images/product-image13.png',
            'http://51.21.2.193/storage/product-images/product-image14.png',
        ];

        Product::all()->each(function ($product) use ($primaryProductImages) {
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $primaryProductImages[array_rand($primaryProductImages)],
                'is_primary' => true
            ]);
        });
    }
}
