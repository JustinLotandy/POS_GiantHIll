<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
   public function __construct()
    {
        $this->middleware('permission:payment_methods.lihat')->only('index');
        $this->middleware('permission:payment_methods.tambah')->only(['create', 'store']);
        $this->middleware('permission:payment_methods.edit')->only(['edit', 'update']);
        $this->middleware('permission:payment_methods.hapus')->only('destroy');
    }
    public function index()
    {
        $payments = PaymentMethod::all();
        return view('payment_methods.index', compact('payments'));
    }

    public function create()
    {
        // Ambil metode terakhir
        $last = \App\Models\PaymentMethod::orderBy('id_PaymentMethod', 'desc')->first();

        $lastId = $last?->id_PaymentMethod ?? null;

        // Generate ID berikutnya otomatis
        if ($lastId && preg_match('/MP-(\d+)/', $lastId, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
            $suggestedId = 'MP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $suggestedId = 'MP-001';
        }

        return view('payment_methods.create', compact('suggestedId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_PaymentMethod' => 'required|string|max:36|unique:payment_methods,id_PaymentMethod',
            'name_payment' => 'required|string|max:100',
        ]);

        PaymentMethod::create($request->only('id_PaymentMethod', 'name_payment'));
        return redirect()->route('payment_methods.index')->with('success', 'Metode pembayaran berhasil ditambah!');
    }

    public function edit($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        return view('payment_methods.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = PaymentMethod::findOrFail($id);
        $request->validate([
            'name_payment' => 'required|string|max:100',
        ]);
        $payment->update($request->only('name_payment'));
        return redirect()->route('payment_methods.index')->with('success', 'Metode pembayaran berhasil diupdate!');
    }

    public function destroy($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        $payment->delete();
        return redirect()->route('payment_methods.index')->with('success', 'Metode pembayaran berhasil dihapus!');
    }
}
