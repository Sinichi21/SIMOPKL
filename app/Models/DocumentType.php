<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'required',
    ];

    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class, 'document_type_id');
    }
}
