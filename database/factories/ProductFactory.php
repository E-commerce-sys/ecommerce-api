<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            ['en' => 'Gaming Mouse', 'ar' => 'فأرة ألعاب', 'ku' => 'مشکی یاری'],
            ['en' => 'Mechanical Keyboard', 'ar' => 'لوحة مفاتيح ميكانيكية', 'ku' => 'کیبۆردی مەکانیکی'],
            ['en' => 'HD Monitor', 'ar' => 'شاشة HD', 'ku' => 'شاشەی HD'],
            ['en' => 'Wireless Headphones', 'ar' => 'سماعات لاسلكية', 'ku' => 'گوێگری بێسیم'],
            ['en' => 'USB-C Hub', 'ar' => 'هاب USB-C', 'ku' => 'هەڵگری USB-C'],
            ['en' => 'Laptop Stand', 'ar' => 'حامل لابتوب', 'ku' => 'هەڵگری لاپتۆپ'],
            ['en' => 'Webcam HD', 'ar' => 'كاميرا ويب HD', 'ku' => 'کامیرای وێبی HD'],
            ['en' => 'SSD 1TB', 'ar' => 'قرص صلب SSD 1TB', 'ku' => 'دیسکی SSD 1TB'],
        ];

        $product = $this->faker->randomElement($products);
        $suffix = $this->faker->bothify('?##');
        $discount = $this->faker->randomElement([10, 15, 20, 25, 30, 35, 40]);
        $stock = $this->faker->numberBetween(0, 200);
        $ratingCount = $this->faker->numberBetween(0, 500);
        $isNewArrival = $this->faker->boolean();
        $hasDiscount = $this->faker->boolean(15);

        return [
            'name_en'              => $product['en'] . ' ' . $suffix,
            'name_ar'              => $product['ar'] . ' ' . $suffix,
            'name_ku'              => $product['ku'] . ' ' . $suffix,
            'description_en'       => $this->faker->paragraph(3),
            'description_ar'       => 'وصف المنتج: ' . $this->faker->paragraph(3),
            'description_ku'       => 'پێناسەی بەرهەم: ' . $this->faker->paragraph(3),
            'category_id'          => Category::where('parent_id', '!=', null)->inRandomOrder()->value('id'),
            'price'                => $this->faker->randomFloat(2, 10, 2000),
            'has_discount'         => $hasDiscount,
            'discount_percentage'  => $hasDiscount ? $discount : 0,
            'is_best_selling'      => $this->faker->boolean(20),
            'is_featured'          => $this->faker->boolean(15),
            'average_rating'       => $ratingCount > 0
                                        ? $this->faker->randomFloat(2, 1, 5)
                                        : 0,
            'rating_count'         => $ratingCount,
            'is_new_arrival' => false,
            'new_arrival_image' => null,
            'is_new' => $this->faker->boolean(10),
        ];
    }
}