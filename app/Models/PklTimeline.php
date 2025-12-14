<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PklTimeline extends Model
{
    use HasFactory;

    protected $table = 'pkl_timelines';

    protected $fillable = [
        'periode_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Timeline milik satu Periode PKL
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
