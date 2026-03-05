<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => [
                'Mobile Phones',
                'Laptops',
                'Cameras',
                'Gaming'
            ],
            'Fashion' => [
                'Men Clothing',
                'Women Clothing',
                'Shoes',
                'Watches'
            ],
            'Home & Kitchen' => [
                'Furniture',
                'Kitchen Appliances',
                'Home Decor'
            ],
            'Beauty & Health' => [
                'Skincare',
                'Makeup',
                'Health Care'
            ],
            'Sports & Outdoors' => [
                'Fitness Equipment',
                'Outdoor Gear',
                'Sportswear'
            ]
        ];

        foreach ($categories as $parent => $children) {

            $parentCategory = Category::create([
                'name' => $parent,
                'icon' => fake()->imageUrl(64, 64, 'business'),
                'parent_id' => null,
            ]);

            foreach ($children as $child) {

                Category::create([
                    'name' => $child,
                    'icon' => fake()->imageUrl(64, 64, 'business'),
                    'parent_id' => $parentCategory->id,
                ]);
            }
        }
    }
}