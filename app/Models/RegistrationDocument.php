<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDocument  extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'document_type_id',
        'file_path',
        'status'
    ];

    public function registration()
    {
        return $this->belongsTo(RegistrationPkl::class, 'registration_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
