<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;
use App\Models\User;

class TransactionController extends Controller
{
   public function __construct()
    {
        // Lihat POS dan checkout
        $this->middleware('permission:transactions.lihat')->only([
            'pos', 'addToCart', 'updateQty', 'removeFromCart',
            'cariBarcode', 'showCheckout', 'checkout', 'struk', 'index'
        ]);

        // Tambah transaksi (jika ada metode store manual)
        $this->middleware('permission:transactions.tambah')->only(['store']);

        // Edit transaksi
        $this->middleware('permission:transactions.edit')->only(['edit', 'update']);

        // Hapus transaksi
        $this->middleware('permission:transactions.hapus')->only(['destroy']);
    }
    // Tampilkan halaman POS
    public function pos()
    {
        $products = Product::all();
        $cart = session('cart', []);
        return view('pos.index', compact('products', 'cart'));
    }

    public function index()
    {
        $transactions = Transaction::with(['paymentMethod', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    // Tambah ke cart (pakai barcode atau id)
    public function addToCart(Request $request)
{
    $id = $request->input('id'); // id_Produk dari form
    $product = Product::where('id_Produk', $id)
                ->orWhere('barcode', $id)
                ->first();

    if (!$product) {
        return back()->with('error', 'Produk tidak ditemukan');
    }

    $cart = session('cart', []);
    $key = $product->id_Produk;

    $cart[$key] = [
        'id' => $product->id_Produk,
        'name' => $product->name,
        'harga_sesudah' => $product->harga_sesudah,
        'qty' => isset($cart[$key]) ? $cart[$key]['qty'] + 1 : 1,
    ];

    session(['cart' => $cart]);
    return redirect()->route('pos.index');
}
    // Update jumlah item
    public function updateQty(Request $request)
    {
        $id = $request->input('id');
        $action = $request->input('action');
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            if ($action === 'increase') {
                $cart[$id]['qty']++;
            } elseif ($action === 'decrease' && $cart[$id]['qty'] > 1) {
                $cart[$id]['qty']--;
            }
        }
        session(['cart' => $cart]);
        return redirect()->route('pos.index');
    }

    // Hapus satu item dari cart
    public function removeFromCart(Request $request)
    {
        $id = $request->input('id');
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }
        session(['cart' => $cart]);
        return redirect()->route('pos.index');
    }

        // Checkout (dummy saja, untuk testing)
   

    public function cariBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = \App\Models\Product::where('barcode', $barcode)->first();

        if ($product) {
            // Langsung add ke cart (logika sama dengan addToCart)
            $cart = session('cart', []);

            // Konsisten key (pakai id_Produk atau id, sesuaikan di semua tempat)
            $key = $product->id_Produk;
            $cart = session('cart', []);

            if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'id' => $product->id_Produk, // <-- pakai id_Produk
                'name' => $product->name,
                'harga_sesudah' => $product->harga_sesudah,
                'qty' => 1,
            ];
        }
        session(['cart' => $cart]);
        return redirect()->route('pos.index')->with('success', 'Produk masuk keranjang: ' . $product->name);
    } else {
        return redirect()->route('pos.index')->with('error', 'Produk tidak ditemukan!');
    }
    
    }
    public function showCheckout()
    {
        $cart = session('cart', []);
        $paymentMethods = PaymentMethod::all();
        return view('pos.checkout', compact('cart', 'paymentMethods'));
    }
    public function checkout(Request $request)
{
    $cart = session('cart', []);
    $bayar = $request->input('bayar');
    $idPaymentMethod = $request->input('id_PaymentMethod');
    $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['harga_sesudah']);

    if ($bayar < $total) {
        return back()->with('error', 'Jumlah bayar kurang dari total.');
    }

    // Simpan transaksi (contoh)
    $trx = \App\Models\Transaction::create([
        // 'id_Transaction'    => (string) Str::uuid(),
        'id_PaymentMethod'  => $idPaymentMethod,
        'total' => $total,
        'paid' => $bayar,
        'change' => $bayar - $total,
        'user_id' => auth()->id(),
    ]);
   foreach ($cart as $item) {
    \App\Models\TransactionItem::create([
        'id_TransactionItem' => (string) Str::uuid(),
        'id_Transaction'    => $trx->id_Transaction,
        'id_Produk' => $item['id'],
        'nama_produk' => $item['name'],
        'quantity' => $item['qty'],
        'price' => $item['harga_sesudah'],
        'subtotal' => $item['qty'] * $item['harga_sesudah'],
    ]);

    // -- Tambahkan ini --
    $product = \App\Models\Product::find($item['id']);
    if ($product) {
        $product->stock = max(0, $product->stock - $item['qty']);
        $product->save();
    }
}


    session()->forget('cart');
    // **REDIRECT KE STRUK!**
    return redirect()->route('pos.struk', $trx->id_Transaction);
}

public function struk($id)
{
    $trx = Transaction::with('items.product', 'user')->findOrFail($id);
    return view('pos.struk', compact('trx'));
}

public function processPayment(Request $request)
{
    $cart = session('cart', []);
    $total = array_sum(array_map(fn($i) => $i['qty'] * $i['harga_sesudah'], $cart));

    $request->validate([
        'amount_paid' => 'required|numeric|min:' . $total,
    ]);

    $change = $request->amount_paid - $total;

    // Di sini simpan transaksi ke database jika mau
    // Transaction::create(...);

    session()->forget('cart');
    return redirect()->route('pos.index')->with('success', 'Pembayaran berhasil! Kembalian: Rp ' . number_format($change, 0, ',', '.'));
}
public function simpanTransaksi(Request $request)
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('pos.index')->with('error', 'Keranjang kosong!');
    }

    $total = collect($cart)->sum(function($i) {
        return $i['qty'] * $i['harga_sesudah'];
    });
    $bayar = $request->input('bayar');
    if ($bayar < $total) {
        return back()->with('error', 'Uang pembayaran kurang!');
    }

    // Di sini simpan transaksi ke database (tambahkan sesuai strukturmu)
    // ... Transaction::create([...])
    // ... TransactionItem::create([...])

    // Setelah simpan, clear cart
    session()->forget('cart');
    return redirect()->route('pos.index')->with('success', 'Transaksi berhasil disimpan!');
}

public function destroy($id)
{
    $transaction = Transaction::findOrFail($id);

    // Jika ingin juga hapus detail transaksi (opsional, jika ada relasi)
    // $transaction->items()->delete(); 

    $transaction->delete();

    return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
}
public function edit($id)
{
    $transaction = Transaction::findOrFail($id);
    $users = User::all();
    $paymentMethods = PaymentMethod::all();

    return view('transactions.edit', compact('transaction', 'users', 'paymentMethods'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'id_PaymentMethod' => 'required|exists:payment_methods,id_PaymentMethod',
        'total' => 'required|numeric|min:0',
        'paid' => 'required|numeric|min:0',
        'change' => 'required|numeric|min:0',
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->update([
        'user_id' => $request->user_id,
        'id_PaymentMethod' => $request->id_PaymentMethod,
        'total' => $request->total,
        'paid' => $request->paid,
        'change' => $request->change,
    ]);

    return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');

}
}