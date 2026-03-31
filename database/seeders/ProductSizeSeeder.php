<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['name' => 'Small',  'size_label' => 'S', 'extra_price' => 0],
            ['name' => 'Medium', 'size_label' => 'M', 'extra_price' => 2.50],
            ['name' => 'Large',  'size_label' => 'L', 'extra_price' => 5.00],
            ['name' => 'XL',     'size_label' => 'XL','extra_price' => 7.50],
            ['name' => 'XXL',    'size_label' => 'XXL','extra_price' => 10.00],
        ];

        $products = Product::where('has_size', true)->get();

        $products->each(function ($product) use ($sizes) {
            $selected = collect($sizes)->shuffle()->take(rand(1, 3));

            foreach ($selected as $size) {
                ProductSize::create([
                    'name'        => $size['name'],
                    'size_label'  => $size['size_label'],
                    'extra_price' => $size['extra_price'],
                    'product_id'  => $product->id,
                ]);
            }
        });
    }
}