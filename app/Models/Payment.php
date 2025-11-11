<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fee_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function paymentSetting()
    {
        return $this->belongsTo(PaymentSetting::class, 'payment_method');
    }

    public function updateStatus(string $newStatus)
    {
        $this->status = $newStatus;
        $this->save();
    }

    public function transactionDetail()
    {
        return $this->hasOne(TransactionDetail::class, 'payment_id');
    }
}
