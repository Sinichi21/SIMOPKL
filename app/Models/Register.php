<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'periode_id',
        'mitra_id',
        'status',
    ];

    // Relasi ke user (mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class);
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
}
