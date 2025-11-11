<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_name',
        'description',
        'email',
        'phone_number',
        'Whatsapp_number',
        'address',
        'website_address',
        'image_url',
        'status',
    ];

    public function registers()
    {
        return $this->hasMany(Register::class, 'mitra_id');
    }
}
