<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logbook extends Model
{
    use HasFactory;
    protected $fillable = [
        'register_id',
        'date',
        'activity_description',
        'status',
    ];

    public function register()
    {
        return $this->belongsTo(Register::class, 'register_id');
    }
}
