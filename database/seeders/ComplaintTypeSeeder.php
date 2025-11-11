<?php

namespace Database\Seeders;

use App\Models\ComplaintType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = array('SK Individu', 'Belum menerima Dana Insentif (publikasi, konferensi, dll)', 'Pengisian LPS "Gagal"', 'Belum diterima Biaya Hidup Bulanan', 'Other');

        foreach ($types as $type) {
            ComplaintType::create([
                'title' => $type
            ]);
        }
    }
}
