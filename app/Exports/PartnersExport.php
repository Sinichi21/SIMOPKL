<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PartnersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $mitras;
    protected $counter = 1;

    public function __construct($mitras)
    {
        $this->mitras = $mitras;
    }

    /**
     * Mengembalikan koleksi data untuk diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->mitras;
    }

    /**
     * Mendefinisikan heading untuk kolom Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Mitra',
            'Deskripsi',
            'No. Telp',
            'Email',
            'No. Whatsapp',
            'Address',
            'Website Address',
            'Status',
        ];
    }

    /**
     * Memetakan data pengguna ke dalam baris Excel.
     *
     * @param mixed $mitra
     * @return array
     */
    public function map($mitra): array
    {
        return [
            $this->counter++,
            $mitra->partner_name ?? 'N/A',
            $mitra->description ?? 'N/A',
            "'" . ($mitra->phone_number ?? 'N/A'),
            $mitra->email,
            "'" . ($mitra->Whatsapp_number ?? 'N/A'),
            $mitra->address ?? 'N/A',
            $mitra->website_address ?? 'N/A',
            $user->status == 1 ? 'Aktif' : 'Non Aktif',
        ];
    }
}
