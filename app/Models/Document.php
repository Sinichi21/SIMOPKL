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
        'user_id',
        'document_type_id',
        'description',
        'admin_note',
        'status',
        'file_path',
        'uploaded_at',
    ];

    public $timestamps = true;

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
}
