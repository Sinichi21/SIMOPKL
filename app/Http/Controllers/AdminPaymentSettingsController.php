<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\PaymentSetting;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPaymentSettingsController extends Controller
{
    public function index()
    {
        $paymentSettings = PaymentSetting::all();
        return view('admin.payment-settings.index', compact('paymentSettings'));
    }

    public function create()
    {
        return view('admin.payment-settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,ewallet',
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'image_file' => 'nullable|mimes:png|max:1024',
        ]);

        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = $request->input('name') . '.png';

            $imagePath = $image->storeAs('payment', $imageName, 'public');
        }

        $validated['image_url'] = isset($imagePath) ? $imagePath : null;

        $paymentSetting = PaymentSetting::create([
            'type' => $request->type,
            'name' => $request->name,
            'account_number' => $request->account_number,
            'image_url' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Payment Settings berhasil ditambahkan'
        ], 201);
    }

    public function edit($id)
    {
        $paymentSetting = PaymentSetting::findOrFail($id);
        return view('admin.payment-settings.edit', compact('paymentSetting'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,ewallet',
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'image_url' => 'nullable|url',
        ]);

        $paymentSetting = PaymentSetting::findOrFail($id);

        if ($request->hasFile('image_file')) {
            if ($paymentSetting->image_url && Storage::exists('public/' . $paymentSetting->image_url)) {
                Storage::delete('public/' . $paymentSetting->image_url);
            }

            $imagePath = $request->file('image_file')->store('payment', 'public');
            $validated['image_url'] = $imagePath;
        }

        $paymentSetting->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'account_number' => $validated['account_number'],
            'image_url' => $validated['image_url'] ?? $paymentSetting->image_url,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Payment Setting berhasil diperbarui'
        ], 200);
    }

    public function delete(Request $request)
    {
        $paymentSetting = PaymentSetting::findOrFail($request->id);

        $paymentSetting->delete();

        return response()->json(['success' => true]);
    }
}
