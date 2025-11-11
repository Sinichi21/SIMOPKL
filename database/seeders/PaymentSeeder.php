<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'user_id' => 103, // ID user
            'fee_id' => 1,  // ID fee
            'payment_method' => 'bank_transfer',
            'amount' => 50000,
            'status' => 'pending',
        ]);
    }
}
