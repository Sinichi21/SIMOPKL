<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'content',
        'status',
        'awardee_id',
        'fullname',
        'bpi_number',
        'faculty',
        'study_program',
        'email',
        'complaint_type_id'
    ];

    public function complaintType(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class);
    }

    public function awardee(): BelongsTo
    {
        return $this->belongsTo(Awardee::class);
    }

    public function complaintMedias(): HasMany
    {
        return $this->hasMany(ComplaintMedia::class);
    }

    public function thread(): HasOne
    {
        return $this->hasOne(Thread::class);
    }
}
