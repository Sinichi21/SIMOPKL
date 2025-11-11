<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'periode_id');
    }
    public function register()
    {
        return $this->belongsToMany(Register::class, 'periode_id');
    }
}
