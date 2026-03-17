<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name_en' => 'PlayStation 5',
            'name_ar' => 'بلايستيشن 5',
            'name_ku' => 'پلەیستەیشن 5',
            'description_en' => 'Black and White version of the PS5 coming out on sale. Experience lightning-fast loading with an ultra-high speed SSD, deeper immersion with support for haptic feedback, adaptive triggers and 3D Audio.',
            'description_ar' => 'الإصدار الأسود والأبيض من PS5 معروض للبيع. استمتع بتحميل سريع للغاية مع SSD فائق السرعة وتجربة غمر أعمق مع دعم التغذية الراجعة اللمسية والمشغلات التكيفية والصوت ثلاثي الأبعاد.',
            'description_ku' => 'وەشانی ڕەش و سپیی PS5 دێتە فرۆشتن. ئەزموونی بارکردنی خێرا بە SSD-ی زۆر خێرا، ئەزموونی قووڵتر لەگەڵ پشتگیری هەستی بەرکەوتن، تریگەرە گونجاوەکان و دەنگی سێ ئەندازەیی.',
            'category_id' => 5,
            'price' => 499.99,
            'has_discount' => true,
            'discount_percentage' => 10,
            'stock_quantity' => 45,
            'is_best_selling' => true,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_new' => false,
            'new_arrival_image' => config('app.server_base_url') . '/storage/banner-images/ps5.png',
            'average_rating' => 4.80,
            'rating_count' => 2340,
        ]);

        Product::create([
            'name_en' => 'Women\'s Collections',
            'name_ar' => 'مجموعات المرأة',
            'name_ku' => 'کۆلەکشنی ژنان',
            'description_en' => 'Featured women collections that give you another vibe. Elegant and stylish fashion pieces crafted for the modern woman who values sophistication and uniqueness.',
            'description_ar' => 'مجموعات نسائية مميزة تمنحك إحساساً مختلفاً. قطع أزياء أنيقة وعصرية مصممة للمرأة الحديثة التي تقدر الرقي والتميز.',
            'description_ku' => 'کۆلەکشنە تایبەتمەندکراوەکانی ژنان کە ئاوای جیاوازت پێ دەدەن. پارچە مۆدە شیک و سەرنجڕاکێشەکان بۆ ژنی مۆدێرن کە قەدر و بەهای ناوازەیی دەزانێت.',
            'category_id' => 8,
            'price' => 89.99,
            'has_discount' => false,
            'discount_percentage' => 0,
            'stock_quantity' => 120,
            'is_best_selling' => false,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_new' => true,
            'new_arrival_image' => config('app.server_base_url') . '/storage/banner-images/woman.png',
            'average_rating' => 4.60,
            'rating_count' => 875,
        ]);

        Product::create([
            'name_en' => 'Amazon Echo Speakers',
            'name_ar' => 'مكبرات صوت أمازون إيكو',
            'name_ku' => 'بڵێنەرەکانی ئامازۆن ئێکۆ',
            'description_en' => 'Amazon wireless speakers with premium 360° sound, built-in Alexa voice assistant, and smart home control. Pack of 3 speakers for whole-home audio coverage.',
            'description_ar' => 'مكبرات صوت لاسلكية من أمازون بصوت ممتاز 360 درجة، ومساعد صوتي مدمج Alexa، والتحكم في المنزل الذكي. حزمة من 3 مكبرات صوت لتغطية صوتية كاملة للمنزل.',
            'description_ku' => 'بڵێنەرە بێسیمەکانی ئامازۆن لەگەڵ دەنگی پریمیۆمی 360 پلە، یاریدەدەری دەنگی Alexa-ی چێشتکراو، و کۆنتڕۆڵی ماڵی زیرەک. پاکێجی 3 بڵێنەر بۆ داگیرکردنی دەنگی تەواوی ماڵ.',
            'category_id' => 2,
            'price' => 149.99,
            'has_discount' => true,
            'discount_percentage' => 15,
            'stock_quantity' => 60,
            'is_best_selling' => true,
            'is_featured' => false,
            'is_new_arrival' => true,
            'is_new' => false,
            'new_arrival_image' => config('app.server_base_url') . '/storage/banner-images/speaker.png',
            'average_rating' => 4.50,
            'rating_count' => 1120,
        ]);

        Product::create([
            'name_en' => 'Gucci Intense Oud EDP',
            'name_ar' => 'عطر غوتشي إنتنس عود',
            'name_ku' => 'پێوەرەی گوچی ئینتێنس عود',
            'description_en' => 'GUCCI INTENSE OUD Eau de Parfum – a bold and luxurious oriental fragrance for men and women. Rich oud wood blended with smoky, spicy, and floral notes for an unforgettable scent.',
            'description_ar' => 'عطر غوتشي إنتنس عود - عطر شرقي جريء وفاخر للرجال والنساء. خشب العود الغني ممزوج بنفحات دخانية وحارة وزهرية لعطر لا يُنسى.',
            'description_ku' => 'پێوەرەی گوچی ئینتێنس عود - پێوەرەیەکی جوان و فاخری ئۆریێنتاڵ بۆ پیاو و ژن. دارستانی عودی بەرزەکار تێکەڵکراوە بە بۆنە دووکەڵین، بازن، و گوڵەکان بۆ بۆنێکی بیرنەچووبۆ.',
            'category_id' => 15,
            'price' => 189.99,
            'has_discount' => false,
            'discount_percentage' => 0,
            'stock_quantity' => 35,
            'is_best_selling' => false,
            'is_featured' => true,
            'is_new_arrival' => true,
            'is_new' => true,
            'new_arrival_image' => config('app.server_base_url') . '/storage/banner-images/gucci.png',
            'average_rating' => 4.90,
            'rating_count' => 530,
        ]);

        Product::factory()->count(250)->create();
    }
}