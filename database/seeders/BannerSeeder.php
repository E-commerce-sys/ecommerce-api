<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Pro. Beyond.',
                'subtitle' => 'Experience the power of the iPhone 14 Pro.',
                'image' => 'https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/banner-images/iphone.png',
                'link' => 'https://example.com/banner1'
            ],
            [
                'title' => 'Massive Sound. Anywhere.',
                'subtitle' => 'Bring the party with the JBL Boombox 3.',
                'image' => 'https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/banner-images/J8LI.png',
                'link' => 'https://example.com/banner2'
            ],
            [
                'title' => 'Deep Purple Elegance',
                'subtitle' => 'Sophisticated design meets unmatched performance.',
                'image' => 'https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/banner-images/iphone.png',
                'link' => 'https://example.com/banner3'
            ],
            [
                'title' => 'Power Your Anthem',
                'subtitle' => 'Iconic design with deep, powerful bass.',
                'image' => 'https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/banner-images/J8LI.png',
                'link' => 'https://example.com/banner4'
            ],
            [
                'title' => 'The Ultimate Upgrade',
                'subtitle' => 'Level up your mobile experience today.',
                'image' => 'https://exclusive-images-intern.s3.eu-north-1.amazonaws.com/all-images/banner-images/iphone.png',
                'link' => 'https://example.com/banner5'
            ]
        ];
        
        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
