<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon; // <-- tambahan
use PDF; // barryvdh/laravel-dompdf

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan.harian')->only(['index','harian']);
        $this->middleware('permission:laporan.mingguan')->only('mingguan');
        $this->middleware('permission:laporan.bulanan')->only('bulanan');
        $this->middleware('permission:laporan.tahunan')->only('tahunan'); // <-- NEW
    }

    public function index()
    {
        return view('laporan.index');
    }

    // HARIAN
    public function harian(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $tanggal = $request->input('tanggal') ?: now($tz)->toDateString();

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereDate('created_at', $tanggal)
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'      => 'Laporan Harian',
            'periode'    => $tanggal,
            'transaksi'  => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-harian-{$tanggal}.pdf");
    }

    // MINGGUAN (support input type="week" -> 2025-W33)
    public function mingguan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $weekInput = $request->input('minggu'); // ex: 2025-W33

        if ($weekInput && preg_match('/^(\d{4})-W(\d{2})$/', $weekInput, $m)) {
            $year = (int) $m[1];
            $week = (int) $m[2];
            // ISO minggu: start Senin, end Minggu
            $start = Carbon::now($tz)->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
            $end   = (clone $start)->endOfWeek(Carbon::SUNDAY);
        } else {
            // fallback: minggu berjalan
            $start = now($tz)->startOfWeek(Carbon::MONDAY);
            $end   = now($tz)->endOfWeek(Carbon::SUNDAY);
        }

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$start->copy()->timezone('UTC'), $end->copy()->timezone('UTC')])
            ->get();

        $periode = $start->format('Y-m-d') . ' s/d ' . $end->format('Y-m-d');

        $pdf = PDF::loadView('laporan.template', [
            'title'      => 'Laporan Mingguan',
            'periode'    => $periode,
            'transaksi'  => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-mingguan-'.$start->format('Y-m-d').'.pdf');
    }

    // BULANAN
    public function bulanan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $bulan = $request->input('bulan') ?: now($tz)->format('Y-m');
        [$tahun, $bulanNum] = explode('-', $bulan);

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereYear('created_at', (int)$tahun)
            ->whereMonth('created_at', (int)$bulanNum)
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'      => 'Laporan Bulanan',
            'periode'    => $bulan,
            'transaksi'  => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-bulanan-{$bulan}.pdf");
    }

    // TAHUNAN (NEW)
    public function tahunan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $tahun = (int) ($request->input('tahun') ?: now($tz)->year);

        $start = Carbon::create($tahun, 1, 1, 0, 0, 0, $tz)->startOfDay();
        $end   = Carbon::create($tahun, 12, 31, 23, 59, 59, $tz)->endOfDay();

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$start->copy()->timezone('UTC'), $end->copy()->timezone('UTC')])
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'      => 'Laporan Tahunan',
            'periode'    => (string)$tahun,
            'transaksi'  => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-tahunan-{$tahun}.pdf");
    }
    // Gunakan partial tabel yang sama untuk semua (lihat poin 4)
public function previewHarian(Request $r){
    $tgl = $r->input('tanggal', now('Asia/Jakarta')->toDateString());
    $data = Transaction::with(['user','paymentMethod'])
            ->whereDate('created_at', $tgl)->latest()->get();
    return view('laporan._tabel', compact('data'));
}

public function previewMingguan(Request $r){
    $tz = 'Asia/Jakarta';
    $week = $r->input('minggu', now($tz)->format('o-\WW'));
    if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $m)) {
        [$all,$y,$w] = $m;
        $start = \Carbon\Carbon::now($tz)->setISODate((int)$y,(int)$w)->startOfWeek();
        $end   = (clone $start)->endOfWeek();
    } else {
        $start = now($tz)->startOfWeek(); $end = now($tz)->endOfWeek();
    }
    $data = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$start, $end])->latest()->get();
    return view('laporan._tabel', compact('data'));
}

public function previewBulanan(Request $r){
    [$y,$m] = explode('-', $r->input('bulan', now('Asia/Jakarta')->format('Y-m')));
    $data = Transaction::with(['user','paymentMethod'])
            ->whereYear('created_at', (int)$y)
            ->whereMonth('created_at', (int)$m)->latest()->get();
    return view('laporan._tabel', compact('data'));
}

public function previewTahunan(Request $r){
    $y = (int) $r->input('tahun', now('Asia/Jakarta')->year);
    $data = Transaction::with(['user','paymentMethod'])
            ->whereYear('created_at', $y)->latest()->get();
    return view('laporan._tabel', compact('data'));
}
}