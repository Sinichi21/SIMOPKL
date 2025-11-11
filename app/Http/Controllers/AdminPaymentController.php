<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\PaymentSetting;
use App\Models\Payment;
use App\Models\TransactionDetail;
use App\Models\User;

class AdminPaymentController extends Controller
{
    public function confirm()
    {
        $pendingPayments = Payment::where('status', 'waiting confirmation')->with(['user.awardee', 'fee', 'transactionDetail'])->get();
        return view('admin.payments.confirm', compact('pendingPayments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user.awardee', 'fee', 'transactionDetail']);

        return view('admin.payments.show', compact('payment'));
    }

    public function approve(Payment $payment)
    {
        $payment->update(['status' => 'success']);
        $payment->transactionDetail->update(['status' => 'success']);

        return redirect()->back()->with('success', 'Pembayaran disetujui.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate(['note' => 'required|string']);

        $payment->update(['status' => 'failed']);
        $payment->transactionDetail->update(['status' => 'failed', 'note' => $validated['note']]);

        return redirect()->back()->with('success', 'Pembayaran ditolak.');
    }
}
