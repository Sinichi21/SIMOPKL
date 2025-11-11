<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\PaymentSetting;
use App\Models\User;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::all();
        return view('admin.fee.index', compact('fees'));
    }

    public function paidAwardees()
    {
        $paidAwardees = Payment::with('user', 'fee')
            ->where('status', 'completed')
            ->get();

        return view('admin.paid-awardees.index', compact('paidAwardees'));
    }

    public function create()
    {
        $paymentMethods = PaymentSetting::all();
        $users = User::join('awardees', 'users.id', '=', 'awardees.user_id')
                ->where('users.status', 1)
                ->where('users.is_registered', 1)
                ->orderBy('awardees.fullname', 'asc')
                ->select('users.*')
                ->get();
        return view('admin.fee.create', compact('users', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|string',
            'repeat_interval' => 'required|integer|min:1',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $validated['amount'] = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $validated['amount']);

        // $amount = (float)$amount;

        $fee = Fee::create([
            'name' => $validated['name'],
            'amount' => $validated['amount'],
            'repeat_interval' => $validated['repeat_interval'],
        ]);

        $fee->payments()->createMany(
            collect($validated['users'])->map(fn($userId) => [
                'user_id' => $userId,
                'fee_id' => $fee->id,
                'amount' => $validated['amount'],
                'status' => 'pending',
            ])->toArray()
        );

        return response()->json([
            'success' => true,
            'msg' => 'Fees berhasil ditambahkan'
        ], 201);
    }

    public function edit($id)
    {
        $fee = Fee::findOrFail($id);
        $selectedUsers = $fee->payments->pluck('user_id')->toArray();
        $users = User::join('awardees', 'users.id', '=', 'awardees.user_id')
                        ->where('users.status', 1)
                        ->where('users.is_registered', 1)
                        ->orderBy('awardees.fullname', 'asc')
                        ->select('users.*')
                        ->get();
        return view('admin.fee.edit', compact('fee', 'users', 'selectedUsers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|string',
            'repeat_interval' => 'required|integer|min:1',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $amount = str_replace(['Rp', '.', ','], ['', '', '.'], $request->amount);
        $amount = (float)$amount;

        $fee = Fee::find($id);
        $fee->update([
            'name' => $request->name,
            'amount' => $amount,
        ]);

        if ($request->has('users')) {
            Payment::where('fee_id', $fee->id)->delete();

            foreach ($request->users as $userId) {
                $paymentMethod = PaymentSetting::first();
                if ($paymentMethod) {
                    Payment::create([
                        'user_id' => $userId,
                        'fee_id' => $fee->id,
                        'payment_method' => $paymentMethod->id,
                        'amount' => $amount,
                        'status' => 'pending',
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Fees berhasil diperbarui'
        ], 200);
    }

    public function delete(Request $request)
    {
        $fee = Fee::findOrFail($request->id);

        $fee->delete();

        return response()->json(['success' => true]);
    }
}
