<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name_en' => 'Electronics',
                'name_ar' => 'الإلكترونيات',
                'name_ku' => 'ئەلیکترۆنیات',
                'children' => [
                    [
                        'name_en' => 'Mobile Phones',
                        'name_ar' => 'الهواتف المحمولة',
                        'name_ku' => 'مۆبایل'
                    ],
                    [
                        'name_en' => 'Laptops',
                        'name_ar' => 'أجهزة اللابتوب',
                        'name_ku' => 'لاپتۆپ'
                    ],
                    [
                        'name_en' => 'Cameras',
                        'name_ar' => 'الكاميرات',
                        'name_ku' => 'کامێرا'
                    ],
                    [
                        'name_en' => 'Gaming',
                        'name_ar' => 'الألعاب',
                        'name_ku' => 'یاریکردن'
                    ],
                ]
            ],

            [
                'name_en' => 'Fashion',
                'name_ar' => 'الأزياء',
                'name_ku' => 'مۆدا',
                'children' => [
                    [
                        'name_en' => 'Men Clothing',
                        'name_ar' => 'ملابس رجالية',
                        'name_ku' => 'جلوبەرگی پیاوان'
                    ],
                    [
                        'name_en' => 'Women Clothing',
                        'name_ar' => 'ملابس نسائية',
                        'name_ku' => 'جلوبەرگی ژنان'
                    ],
                    [
                        'name_en' => 'Shoes',
                        'name_ar' => 'الأحذية',
                        'name_ku' => 'پێڵاو'
                    ],
                    [
                        'name_en' => 'Watches',
                        'name_ar' => 'الساعات',
                        'name_ku' => 'کاتژمێر'
                    ],
                ]
            ],

            [
                'name_en' => 'Home & Kitchen',
                'name_ar' => 'المنزل والمطبخ',
                'name_ku' => 'ماڵ و چێشتخانە',
                'children' => [
                    [
                        'name_en' => 'Furniture',
                        'name_ar' => 'الأثاث',
                        'name_ku' => 'کەل و پەل'
                    ],
                    [
                        'name_en' => 'Kitchen Appliances',
                        'name_ar' => 'أجهزة المطبخ',
                        'name_ku' => 'ئامێرەکانی چێشتخانە'
                    ],
                    [
                        'name_en' => 'Home Decor',
                        'name_ar' => 'ديكور المنزل',
                        'name_ku' => 'ڕازاندنەوەی ماڵ'
                    ],
                ]
            ],

            [
                'name_en' => 'Beauty & Health',
                'name_ar' => 'الجمال والصحة',
                'name_ku' => 'جوانی و تەندروستی',
                'children' => [
                    [
                        'name_en' => 'Skincare',
                        'name_ar' => 'العناية بالبشرة',
                        'name_ku' => 'چاودێری پێست'
                    ],
                    [
                        'name_en' => 'Makeup',
                        'name_ar' => 'مكياج',
                        'name_ku' => 'میکاپ'
                    ],
                    [
                        'name_en' => 'Health Care',
                        'name_ar' => 'الرعاية الصحية',
                        'name_ku' => 'چاودێری تەندروستی'
                    ],
                ]
            ],

            [
                'name_en' => 'Sports & Outdoors',
                'name_ar' => 'الرياضة والأنشطة الخارجية',
                'name_ku' => 'وەرزش و دەرەوە',
                'children' => [
                    [
                        'name_en' => 'Fitness Equipment',
                        'name_ar' => 'معدات اللياقة',
                        'name_ku' => 'ئامێرەکانی فیتنەس'
                    ],
                    [
                        'name_en' => 'Outdoor Gear',
                        'name_ar' => 'معدات خارجية',
                        'name_ku' => 'کەلوپەلی دەرەوە'
                    ],
                    [
                        'name_en' => 'Sportswear',
                        'name_ar' => 'ملابس رياضية',
                        'name_ku' => 'جلوبەرگی وەرزشی'
                    ],
                ]
            ],

        ];

        foreach ($categories as $category) {

            $parent = Category::create([
                'name_en' => $category['name_en'],
                'name_ar' => $category['name_ar'],
                'name_ku' => $category['name_ku'],
                'icon' => fake()->imageUrl(64, 64, 'business'),
                'parent_id' => null,
            ]);

            foreach ($category['children'] as $child) {

                Category::create([
                    'name_en' => $child['name_en'],
                    'name_ar' => $child['name_ar'],
                    'name_ku' => $child['name_ku'],
                    'icon' => fake()->imageUrl(64, 64, 'business'),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}