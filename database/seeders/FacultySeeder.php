<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = ['Farmasi', 'Hukum', 'Ilmu Administrasi', 'Ilmu Pengetahuan Budaya', 'Ekonomi dan Bisnis', 'Ilmu Keperawatan', 'Ilmu Komputer', 'Imlu Sosial dan Ilmu Politik', 'Kedokteran', 'Kedokteran Gigi', 'Kesehatan Masyarakat', 'Matematika dan Ilmu Pengetahuan Alam', 'Psikologi', 'Teknik', 'Pendidikan Vokasi', 'Sekolah ilmu Lingkungan', 'Sekolah Kajian Stratejik dan Global',];

        foreach ($faculties as $faculty) {
            Faculty::create([
                'name' => $faculty
            ]);
        }
    }
}
