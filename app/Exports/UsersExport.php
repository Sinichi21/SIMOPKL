<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $users;
    protected $counter = 1;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Mengembalikan koleksi data untuk diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->users;
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
            'Nama Lengkap',
            'Nama Panggilan',
            'No. Telp',
            'Email',
            'No. BPI',
            'Jenjang',
            'Tahun Awardee BPI',
            'Fakultas',
            'Program Studi',
            'Status',
        ];
    }

    /**
     * Memetakan data pengguna ke dalam baris Excel.
     *
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $this->counter++,
            $user->awardee->fullname ?? 'N/A',
            $user->awardee->username ?? 'N/A',
            "'" . ($user->awardee->phone_number ?? 'N/A'),
            $user->email,
            "'" . ($user->awardee->bpi_number ?? 'N/A'),
            $user->awardee->degree ?? 'N/A',
            $user->awardee->year ?? 'N/A',
            $user->awardee->studyProgram->faculty->name ?? 'N/A',
            $user->awardee->studyProgram->name ?? 'N/A',
            $user->status == 1 ? 'Aktif' : 'Non Aktif',
        ];
    }
}
