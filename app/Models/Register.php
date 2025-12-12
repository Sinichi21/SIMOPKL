<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'awardee_id',
        'periode_id',
        'mitra_id',
        'fullname',
        'nim',
        'faculty',
        'study_program',
        'email',
        'status',
        'unit',
        'start_date',
        'end_date',
    ];

    // Relasi ke awardee (mahasiswa)
    public function awardee()
    {
        return $this->belongsTo(Awardee::class);
    }

    // Relasi ke periode PKL
    public function periode()
    {
        return $this->belongsTo(PeriodePkl::class, 'periode_id');
    }

    // Relasi ke mitra
    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    // Relasi ke logbook
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    // Relasi ke dokumen registrasi
    public function registrationDocuments()
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_id');
    }

    public function calculateProgress()
    {
        $progress = 0;

        // Jika registrasi disetujui → tambah 20%
        if ($this->status === 'Disetujui') {
            $progress += 20;
        }

        // Hitung jumlah dokumen yang sudah disetujui
        $approvedDocs = $this->registrationDocuments()
            ->where('status', 'Disetujui')
            ->count();

        // Maksimal dokumen hanya 4
        $maxDocs = 4;

        // Setiap dokumen bernilai 20%
        $progress += ($approvedDocs * 20);

        // Batas maksimal tidak lebih dari 100
        return min($progress, 100);
    }
}
