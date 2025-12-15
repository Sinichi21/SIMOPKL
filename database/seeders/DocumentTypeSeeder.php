<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            ['name' => 'Transkrip Nilai', 'label' => 'transkrip_nilai', 'required' => 1],
            ['name' => 'SK Penerimaan Mitra PKL', 'label' => 'sk_penerimaan_mitra', 'required' => 1],
            ['name' => 'Form 1A', 'label' => 'form_1a', 'required' => 0],
            ['name' => 'Form 2A', 'label' => 'form_2a', 'required' => 0],
            ['name' => 'Form 2B', 'label' => 'form_2b', 'required' => 0],
            ['name' => 'Form 2C', 'label' => 'form_2c', 'required' => 0],
            ['name' => 'Form 3A', 'label' => 'form_3a', 'required' => 0],
            ['name' => 'Form 3C', 'label' => 'form_3c', 'required' => 0],
            ['name' => 'Form 6B', 'label' => 'form_6b', 'required' => 0],
            ['name' => 'Form 8A', 'label' => 'form_8a', 'required' => 0],
            ['name' => 'Surat Tugas Pengabdian', 'label' => 'surat_tugas_pengabdian', 'required' => 0],
            ['name' => 'Form 4A', 'label' => 'form_4a', 'required' => 1],
            ['name' => 'Form 4B', 'label' => 'form_4b', 'required' => 1],
            ['name' => 'Form 5A', 'label' => 'form_5a', 'required' => 1],
            ['name' => 'Form 7A', 'label' => 'form_7a', 'required' => 1],
            ['name' => 'Daftar Hadir Observasi', 'label' => 'daftar_hadir_observasi', 'required' => 1],
            ['name' => 'Draft Laporan Pengabdian', 'label' => 'draft_laporan_pengabdian', 'required' => 1],
            ['name' => 'Draft Jurnal Jupita', 'label' => 'draft_jurnal_jupita', 'required' => 1],
            ['name' => 'Permohonan Berita Acara', 'label' => 'permohonan_berita_acara', 'required' => 1],
            ['name' => 'Form Revisi Penguji', 'label' => 'form_revisi_penguji', 'required' => 1],
            ['name' => 'Nilai Ujian PKL', 'label' => 'nilai_ujian_pkl', 'required' => 1],
            ['name' => 'Jurnal Final', 'label' => 'jurnal_final', 'required' => 1],
            ['name' => 'Laporan Akhir', 'label' => 'laporan_akhir', 'required' => 1],
            ['name' => 'Berita Acara Ujian', 'label' => 'berita_acara_ujian', 'required' => 1],
            ['name' => 'Nilai Akhir', 'label' => 'nilai_akhir', 'required' => 1],
        ];

        foreach ($documentTypes as $type) {
            DocumentType::create($type);
        }
    }
}
