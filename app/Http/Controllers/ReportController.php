<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF; // pastikan sudah install barryvdh/laravel-dompdf

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan.harian')->only('index');
        $this->middleware('permission:laporan.harian')->only('harian');
        $this->middleware('permission:laporan.mingguan')->only('mingguan');
        $this->middleware('permission:laporan.bulanan')->only('bulanan');
    }

    // Tampil halaman tombol laporan
    public function index()
    {
        return view('laporan.index');
    }

    // Cetak laporan harian
    public function harian(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? now()->toDateString();
        $transaksi = Transaction::with(['user', 'paymentMethod'])
            ->whereDate('created_at', $tanggal)
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title' => 'Laporan Harian',
            'periode' => $tanggal,
            'transaksi' => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-harian-'.$tanggal.'.pdf');
    }

    // Cetak laporan mingguan
    public function mingguan(Request $request)
    {
        $mingguIni = now()->startOfWeek();
        $mingguAkhir = now()->endOfWeek();
        $transaksi = Transaction::with(['user', 'paymentMethod'])
            ->whereBetween('created_at', [$mingguIni, $mingguAkhir])
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title' => 'Laporan Mingguan',
            'periode' => $mingguIni->format('Y-m-d').' s/d '.$mingguAkhir->format('Y-m-d'),
            'transaksi' => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-mingguan-'.$mingguIni->format('Y-m-d').'.pdf');
    }

    // Cetak laporan bulanan
    public function bulanan(Request $request)
    {
        $bulan = $request->input('bulan') ?? now()->format('Y-m');
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);
        $transaksi = Transaction::with(['user', 'paymentMethod'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulanNum)
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title' => 'Laporan Bulanan',
            'periode' => $bulan,
            'transaksi' => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-bulanan-'.$bulan.'.pdf');
    }
}
