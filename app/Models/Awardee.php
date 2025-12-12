<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Awardee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'username',
        'nim',
        'degree',
        'phone_number',
        'user_id',
        'study_program_id',
        'year',
        'transkrip_nilai',
    ];

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registers()
    {
        return $this->hasMany(Register::class);
    }

    public function hasActiveRegistration($periodeId)
    {
        return $this->registers()
            ->where('periode_id', $periodeId)
            ->whereIn('status', ['pending', 'Disetujui'])
            ->exists();
    }
}
