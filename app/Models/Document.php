<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'name',
        'description',
        'file_path',
        'uploaded_at',
    ];

    public $timestamps = true;

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
}
