<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\PaymentSetting;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionDetailController extends Controller
{
    public function show(TransactionDetail $transaction)
    {
        $user = auth()->user();

        $fees = Fee::whereDoesntHave('payments', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereIn('status', ['success'])
                ->groupBy('fee_id')
                ->havingRaw('COUNT(*) >= fees.repeat_interval');
        })->orWhereHas('payments', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'in progress', 'waiting confirmation', 'success', 'failed'])
                ->whereRaw('DATE_ADD(created_at, INTERVAL (12 / fees.repeat_interval) MONTH) <= NOW()');
        })->get();

        $payments = Payment::where('user_id', $user->id)
            ->with('fee')
            ->get();

        return view('payments.transaction_detail', compact('transaction', 'fees', 'payments'));
    }

    public function confirm(Payment $payment)
    {
        return view('payments.confirm', compact('payment'));
    }

    public function store(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'proof_of_payment' => 'required|image|max:1024',
            'transaction_note' => 'nullable|string',
        ]);

        $path = $validated['proof_of_payment']->store('proofs', 'public');

        $payment->update([
            'status' => 'waiting confirmation',
        ]);

        TransactionDetail::create([
            'user_id' => auth()->id(),
            'fee_id' => $payment->fee_id,
            'payment_id' => $payment->id,
            'payment_method_id' => $payment->payment_method,
            'transaction_note' => $validated['transaction_note'],
            'proof_of_payment' => $path,
            'amount' => $payment->amount,
            'status' => 'waiting confirmation',
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Konfirmasi pembayaran berhasil. Silahkan untuk menunggu konfirmasi selanjutnya.'
        ], 201);
    }
}
