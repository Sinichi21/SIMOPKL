<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\TransactionDetail;
use App\Models\PaymentSetting;

class PaymentController extends Controller
{
    public function index()
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

        $transactionDetails = TransactionDetail::all();

        return view('payments.index', compact('fees', 'payments', 'transactionDetails'));
    }

    public function pay(Fee $fee)
    {
        $paymentMethods = PaymentSetting::all();
        return view('payments.pay', compact('fee', 'paymentMethods'));
    }

    public function store(Request $request, Fee $fee)
    {

        $validated = $request->validate([
            'payment_method' => 'required|exists:payment_settings,id',
        ]);

        $payment = Payment::where('user_id', auth()->id())
            ->where('fee_id', $fee->id)
            ->orderByDesc('created_at')
            ->first();

        if ($payment) {
            $payment->update([
                'payment_method' => $validated['payment_method'],
                'amount' => $fee->amount,
                'status' => 'in progress',
            ]);

            $message = 'Pembayaran telah diperbarui, lakukan konfirmasi segera.';
        } else {
            Payment::create([
                'user_id' => auth()->id(),
                'fee_id' => $fee->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $fee->amount,
                'status' => 'in progress',
            ]);

            $message = 'Pembayaran sedang dalam proses, lakukan konfirmasi segera.';
        }

        return response()->json([
            'success' => true,
            'msg' => 'Pembayaran sedang dalam proses, lakukan konfirmasi segera',
        ], 200);
    }

    public function show($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);

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

        if (!$payment) {
            return redirect()->route('payments.index')->with('error', 'Payment tidak ditemukan.');
        }

        return view('payments.payment_detail', compact('payment', 'payments', 'fees'));
    }
}
