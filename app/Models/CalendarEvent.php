<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'title', 'start_time', 'end_time', 'location'];

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('g:i a');
    }
}
