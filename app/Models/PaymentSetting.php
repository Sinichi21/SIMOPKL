<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'account_number',
        'image_url',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method');
    }
}
