<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Black',  'hex_code' => '#000000'],
            ['name' => 'White',  'hex_code' => '#FFFFFF'],
            ['name' => 'Red',    'hex_code' => '#FF0000'],
            ['name' => 'Blue',   'hex_code' => '#0000FF'],
            ['name' => 'Green',  'hex_code' => '#008000'],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00'],
            ['name' => 'Gray',   'hex_code' => '#808080'],
            ['name' => 'Silver', 'hex_code' => '#C0C0C0'],
            ['name' => 'Gold',   'hex_code' => '#FFD700'],
            ['name' => 'Navy',   'hex_code' => '#000080'],
            ['name' => 'Pink',   'hex_code' => '#FFC0CB'],
            ['name' => 'Orange', 'hex_code' => '#FFA500'],
        ];

        $allProducts = Product::all();
        $halfCount = ceil($allProducts->count() / 2);

        // Pick 50% of products randomly
        $productsToColor = $allProducts->shuffle()->take($halfCount);

        $productsToColor->each(function ($product) use ($colors) {
            // Assign 1–3 random unique colors per selected product
            $selected = collect($colors)->shuffle()->take(rand(1, 3));

            foreach ($selected as $color) {
                ProductColor::create([
                    'name'           => $color['name'],
                    'hex_code'       => $color['hex_code'],
                    'product_id'     => $product->id,
                ]);
            }
        });
    }
}