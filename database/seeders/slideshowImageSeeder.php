<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\slideshowImage;
use Illuminate\Database\Seeder;

class slideshowImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        slideshowImage::create([
            'url' => 'carousel/slideshow-1.jpg',
        ]);
        slideshowImage::create([
            'url' => 'carousel/slideshow-2.jpg',
        ]);
        slideshowImage::create([
            'url' => 'carousel/slideshow-3.jpg',
        ]);
    }
}
