<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MenuIcon;
use Illuminate\Database\Seeder;

class MenuIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuIcon::create([
            'url' => 'iconmenu/faq.jpg',
            'menu' => 'FaQ',
        ]);
        MenuIcon::create([
            'url' => 'iconmenu/pengaduan.jpg',
            'menu' => 'Pengaduan',
        ]);
        MenuIcon::create([
            'url' => 'iconmenu/awardee.jpg',
            'menu' => 'Awardee',
        ]);
        MenuIcon::create([
            'url' => 'iconmenu/galeri.jpg',
            'menu' => 'Gallery',
        ]);
    }
}
