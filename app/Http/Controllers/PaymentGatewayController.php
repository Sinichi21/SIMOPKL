<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    public function showFees()
    {
        $fees = Fee::all();
        $payments = Payment::where('user_id', auth()->id())->get();

        return view('payments.index', compact('fees', 'payments'));
    }

    public function showPaymentForm()
    {
        $paymentSettings = PaymentSetting::all();
        $fees = Fee::all();
        return view('payments.create', compact('fees', 'paymentSettings'));
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|exists:payment_settings,id',
            'fee_id' => 'required|exists:fees,id',
        ]);

        $paymentMethodId = $validated['payment_method'];
        $feeId = $validated['fee_id'];
        $paymentSetting = PaymentSetting::findOrFail($paymentMethodId);
        $fee = Fee::findOrFail($feeId);
        $user = auth()->user();

        $alertMessage = "Nomor rekening ID/e-wallet berlaku selama 24 jam: {$paymentSetting->account_number}";

        $transactionId = strtoupper(str_random(8));

        return response()->json([
            'alert' => $alertMessage,
            'payment_method' => $paymentSetting,
            'fee' => $fee,
            'transaction_id' => $transactionId,
            'success' => true
        ]);
    }

    public function showPaymentStatus($transaction_id)
    {
        $payment = Payment::where('transaction_id', $transaction_id)->firstOrFail();

        // Check payment status from Xendit
        Xendit::setApiKey(env('XENDIT_API_KEY'));

        if ($payment->paymentSetting->type === 'virtual_account') {
            $response = Xendit\VirtualAccounts::retrieve($transaction_id);
        } elseif ($payment->paymentSetting->type === 'e_wallet') {
            $response = Xendit\EWallets::retrieve($transaction_id);
        } else {
            abort(404);
        }

        $payment->status = $response['status'];
        $payment->save();

        return view('payments.status', compact('payment'));
    }
}
