<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'title' => 'Banner 1',
                'subtitle' => 'Subtitle 1',
                'image' => 'https://picsum.photos/seed/ad/600/400',
                'link' => 'https://example.com/banner1'
            ],
            [
                'title' => 'Banner 2',
                'subtitle' => 'Subtitle 2',
                'image' => 'https://picsum.photos/seed/ae/600/400',
                'link' => 'https://example.com/banner2'
            ],
            [
                'title' => 'Banner 3',
                'subtitle' => 'Subtitle 3',
                'image' => 'https://picsum.photos/seed/af/600/400',
                'link' => 'https://example.com/banner3'
            ],
            [
                'title' => 'Banner 4',
                'subtitle' => 'Subtitle 4',
                'image' => 'https://picsum.photos/seed/ag/600/400',
                'link' => 'https://example.com/banner4'
            ],
            [
                'title' => 'Banner 5',
                'subtitle' => 'Subtitle 5',
                'image' => 'https://picsum.photos/seed/ah/600/400',
                'link' => 'https://example.com/banner5'
            ]
        ];
        
        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
