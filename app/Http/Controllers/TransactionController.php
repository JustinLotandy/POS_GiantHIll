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
use Carbon\Carbon;

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
        $transactions = Transaction::with(['paymentMethod', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    // =========================
    //  Tambah ke cart (id/barcode)  -- VALIDASI STOK
    // =========================
    public function addToCart(Request $request)
    {
        $id = $request->input('id'); // id_Produk dari form
        $product = Product::where('id_Produk', $id)
            ->orWhere('barcode', $id)
            ->first();

        if (!$product) {
            return redirect()->route('pos.index');
        }

        $cart = session('cart', []);
        $key  = $product->id_Produk;
        $currentQty = isset($cart[$key]) ? (int) $cart[$key]['qty'] : 0;

        // 1) Stok 0 -> tidak boleh ditambahkan
        if ((int) $product->stock <= 0) {
            return redirect()->route('pos.index');
        }

        // 2) Melebihi stok -> blok
        if ($currentQty + 1 > (int) $product->stock) {
            return redirect()->route('pos.index');
        }

        // 3) Aman -> tambahkan
        $cart[$key] = [
            'id'            => $product->id_Produk,
            'name'          => $product->name,
            'harga_sesudah' => $product->harga_sesudah,
            'qty'           => $currentQty + 1,
        ];

        session(['cart' => $cart]);

        return redirect()->route('pos.index');
    }

    // Update jumlah item (tingkatkan/kurangi) — proteksi stok saat increase
    public function updateQty(Request $request)
    {
        $id     = $request->input('id');     // id_Produk
        $action = $request->input('action'); // increase / decrease
        $cart   = session('cart', []);

        if (isset($cart[$id])) {
            if ($action === 'increase') {
                // Cek stok produk sebelum menambah
                $product = Product::find($id);
                if ($product) {
                    $nextQty = (int) $cart[$id]['qty'] + 1;
                    if ($nextQty > (int) $product->stock) {
                        return redirect()->route('pos.index');
                    }
                }
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

    // =========================
    //  Scan / cari barcode -- VALIDASI STOK
    // =========================
    public function cariBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return redirect()->route('pos.index');
        }

        $cart = session('cart', []);
        $key  = $product->id_Produk;
        $currentQty = isset($cart[$key]) ? (int) $cart[$key]['qty'] : 0;

        // Validasi stok
        if ((int) $product->stock <= 0) {
            return redirect()->route('pos.index');
        }
        if ($currentQty + 1 > (int) $product->stock) {
            return redirect()->route('pos.index');
        }

        // Tambah ke cart
        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'id'            => $product->id_Produk, // pakai id_Produk
                'name'          => $product->name,
                'harga_sesudah' => $product->harga_sesudah,
                'qty'           => 1,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('pos.index');
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
            return redirect()->route('pos.checkout'); // atau back() sesuai alurmu
        }

        // Simpan transaksi
        $trx = Transaction::create([
            // 'id_Transaction'   => (string) Str::uuid(),
            'id_PaymentMethod'  => $idPaymentMethod,
            'total'             => $total,
            'paid'              => $bayar,
            'change'            => $bayar - $total,
            'user_id'           => auth()->id(),
        ]);

        foreach ($cart as $item) {
            TransactionItem::create([
                'id_TransactionItem' => (string) Str::uuid(),
                'id_Transaction'     => $trx->id_Transaction,
                'id_Produk'          => $item['id'],
                'nama_produk'        => $item['name'],
                'quantity'           => $item['qty'],
                'price'              => $item['harga_sesudah'],
                'subtotal'           => $item['qty'] * $item['harga_sesudah'],
            ]);

            // Kurangi stok produk
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['qty']);
                $product->save();
            }
        }

        session()->forget('cart');

        // Redirect ke struk
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

        // Simpan transaksi bila diperlukan
        // Transaction::create(...);

        session()->forget('cart');
        return redirect()->route('pos.index');
    }

    public function simpanTransaksi(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('pos.index');
        }

        $total = collect($cart)->sum(function ($i) {
            return $i['qty'] * $i['harga_sesudah'];
        });

        $bayar = $request->input('bayar');
        if ($bayar < $total) {
            return redirect()->route('pos.index');
        }

        // Simpan transaksi manual di sini jika diperlukan
        // ...

        session()->forget('cart');
        return redirect()->route('pos.index');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Jika ingin juga hapus detail transaksi (opsional, jika ada relasi)
        // $transaction->items()->delete();

        $transaction->delete();

        return redirect()->route('transactions.index');
    }

    public function edit($id)
    {
        $transaction   = Transaction::findOrFail($id);
        $users         = User::all();
        $paymentMethods= PaymentMethod::all();

        return view('transactions.edit', compact('transaction', 'users', 'paymentMethods'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_PaymentMethod' => 'required|exists:payment_methods,id_PaymentMethod',
            'total'            => 'required|numeric|min:0',
            'paid'             => 'required|numeric|min:0',
            'change'           => 'required|numeric|min:0',
            'created_at'       => 'required|date', // validasi tanggal (edit saja)
        ]);

        $transaction = Transaction::findOrFail($id);

        // field biasa
        $transaction->id_PaymentMethod = $request->id_PaymentMethod;
        $transaction->total            = $request->total;
        $transaction->paid             = $request->paid;
        $transaction->change           = $request->change;

        // tanggal transaksi (pakai created_at)
        $transaction->created_at = Carbon::parse($request->created_at);

        $transaction->save();

        return redirect()->route('transactions.index');
    }
}
