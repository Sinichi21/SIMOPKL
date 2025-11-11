<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fee_id',
        'payment_id',
        'payment_method_id',
        'transaction_note',
        'proof_of_payment',
        'amount',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function paymentSetting()
    {
        return $this->belongsTo(PaymentSetting::class, 'payment_method_id');
    }
}
