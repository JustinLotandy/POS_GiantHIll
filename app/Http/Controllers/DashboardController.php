<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $totalKategori = Category::count();
        $today = now()->format('Y-m-d');
        $totalTransaksiHariIni = Transaction::whereDate('created_at', $today)->count();
        $totalOmzetHariIni = Transaction::whereDate('created_at', $today)->sum('total');

        // Hanya produk stok < 15
        $produkStokMinimum = Product::where('stock', '<', 15)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Grafik omzet 7 hari terakhir
        $labels = [];
        $dataOmzet = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $dataOmzet[] = Transaction::whereDate('created_at', $tanggal)->sum('total');
        }

        return view('dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalTransaksiHariIni',
            'totalOmzetHariIni',
            'produkStokMinimum',
            'labels',
            'dataOmzet'
        ));
    }
}
