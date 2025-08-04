<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $paymentMethods = PaymentMethod::all();
        return view('pos.index', compact('products', 'paymentMethods'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'payment_method_id' => 'required',
            'paid' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
        }

        if ($request->paid < $total) {
            return back()->with('error', 'Bayar kurang dari total');
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'payment_method_id' => $request->payment_method_id,
            'total' => $total,
            'paid' => $request->paid,
            'change' => $request->paid - $total,
        ]);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['id']);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity'],
            ]);

            $product->decrement('stock', $item['quantity']);
        }

        return redirect()->route('pos.invoice', $transaction->id);
    }

    public function invoice($id)
    {
        $transaction = Transaction::with('items.product', 'user', 'paymentMethod')->findOrFail($id);
        return view('pos.invoice', compact('transaction'));
    }
}
