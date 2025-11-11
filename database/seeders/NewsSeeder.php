<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Tender Pengadaan Kendaraan Dinas Fakultas Ilmu Pengetahuan Budaya Universitas Indonesia',
            'description' => 'Deskripsi singkat atau isi berita.',
            'image' => 'news/new_2.jpeg',
            'published_at' => '2024-08-09',
            'author' => 'Admin'
        ]);

        News::create([
            'title' => 'Tender Pengadaan Kendaraan Operasional Fakultas Farmasi UI',
            'description' => 'Deskripsi singkat atau isi berita.',
            'image' => 'news/new_2.jpeg',
            'published_at' => '2024-08-08',
            'author' => 'Admin'
        ]);

        News::create([
            'title' => 'Tender Pengadaan Kendaraan Dinas (Mobil) FIK UI 2024',
            'description' => 'Deskripsi singkat atau isi berita.',
            'image' => 'news/new_3.jpeg',
            'published_at' => '2024-07-23',
            'author' => 'Admin'
        ]);

        News::create([
            'title' => 'Tender Pengadaan Perpanjangan ATS Oracle E-Business Suite, Oracle Hyperion, dan Oracle Database Universitas Indonesia Tahun 2024-2025',
            'description' => 'Deskripsi singkat atau isi berita.',
            'image' => 'news/new_4.jpeg',
            'published_at' => '2024-07-22',
            'author' => 'Admin'
        ]);
    }
}
