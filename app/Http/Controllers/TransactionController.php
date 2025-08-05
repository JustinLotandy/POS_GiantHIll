<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'paymentMethod'])->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $paymentMethods = PaymentMethod::all();
        return view('transactions.create', compact('paymentMethods'));
    }

   public function edit($id)
{
    $transaction = \App\Models\Transaction::findOrFail($id);
    $paymentMethods = \App\Models\PaymentMethod::all();
    // Ambil user untuk select, atau cukup tampilkan user saat ini saja
    return view('transactions.edit', compact('transaction', 'paymentMethods'));
}

public function update(Request $request, $id)
{
    $transaction = \App\Models\Transaction::findOrFail($id);

    $request->validate([
        'id_PaymentMethod' => 'required|exists:payment_methods,id_PaymentMethod',
        'total'   => 'required|numeric',
        'paid'    => 'required|numeric',
        'change'  => 'required|numeric',
        // user_id biasanya tidak diubah (tapi bisa ditambahkan jika perlu)
    ]);

    $data = $request->only(['id_PaymentMethod', 'total', 'paid', 'change']);
    $transaction->update($data);

    return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diupdate!');
}
}
