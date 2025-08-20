<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use PDF; // barryvdh/laravel-dompdf

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan.harian')->only(['index','harian']);
        $this->middleware('permission:laporan.mingguan')->only('mingguan');
        $this->middleware('permission:laporan.bulanan')->only('bulanan');
        $this->middleware('permission:laporan.tahunan')->only('tahunan');
    }

    public function index()
    {
        return view('laporan.index');
    }

    /** Toggle UTC dari .env: REPORT_USE_UTC=true|false (default false) */
    private function useUtc(): bool
    {
        return filter_var(config('app.report_use_utc', env('REPORT_USE_UTC', false)), FILTER_VALIDATE_BOOLEAN);
    }

    /** Range untuk whereBetween sesuai mode (UTC / lokal) */
    private function rangeForQuery(Carbon $startLocal, Carbon $endLocal): array
    {
        if ($this->useUtc()) {
            return [$startLocal->copy()->timezone('UTC'), $endLocal->copy()->timezone('UTC')];
        }
        return [$startLocal, $endLocal];
    }

    /** Tempelkan total_modal & total_profit ke setiap transaksi (butuh eager-load items.product) */
    private function attachPerTxnMetrics($transactions): void
    {
        foreach ($transactions as $trx) {
            $totalModal  = 0; // SUM(harga_sebelum * qty)
            $totalProfit = 0; // SUM((price_jual - harga_sebelum) * qty)

            foreach ($trx->items ?? [] as $it) {
                $qty   = (int) ($it->quantity ?? 0);
                $jual  = (int) ($it->price ?? 0); // harga saat transaksi (bukan dari product)
                $modal = (int) optional($it->product)->harga_sebelum;

                $totalModal  += $modal * $qty;
                $totalProfit += max(0, $jual - $modal) * $qty;
            }

            $trx->setAttribute('total_modal',  $totalModal);
            $trx->setAttribute('total_profit', $totalProfit);
        }
    }

    /** Hitung ringkasan global (omzet, profit, jumlah transaksi) */
    private function computeSummary($transactions): array
    {
        $omzet = 0;
        $profit = 0;
        $count = $transactions->count();

        foreach ($transactions as $trx) {
            $omzet += (int) ($trx->total ?? 0);

            // Pakai metrik yang sudah ditempel agar konsisten
            $profit += (int) ($trx->total_profit ?? 0);
        }

        return [
            'omzet'  => $omzet,
            'profit' => $profit,
            'count'  => $count,
        ];
    }

    // ================== CETAK PDF ==================

    public function harian(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $tanggal = $request->input('tanggal') ?: now($tz)->toDateString();

        $start = Carbon::parse($tanggal, $tz)->startOfDay();
        $end   = Carbon::parse($tanggal, $tz)->endOfDay();
        [$startQ, $endQ] = $this->rangeForQuery($start, $end);

        $transaksi = Transaction::with(['user','paymentMethod','items.product'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')->get();

        $this->attachPerTxnMetrics($transaksi);
        $summary = $this->computeSummary($transaksi);

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Harian',
            'periode'   => $tanggal,
            'transaksi' => $transaksi,
            'summary'   => $summary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-harian-{$tanggal}.pdf");
    }

    public function mingguan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $weekInput = $request->input('minggu'); // ex: 2025-W33

        if ($weekInput && preg_match('/^(\d{4})-W(\d{2})$/', $weekInput, $m)) {
            $year = (int) $m[1];
            $week = (int) $m[2];
            $start = Carbon::now($tz)->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
            $end   = (clone $start)->endOfWeek(Carbon::SUNDAY);
        } else {
            $start = now($tz)->startOfWeek(Carbon::MONDAY);
            $end   = now($tz)->endOfWeek(Carbon::SUNDAY);
        }

        [$startQ, $endQ] = $this->rangeForQuery($start, $end);

        $transaksi = Transaction::with(['user','paymentMethod','items.product'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')->get();

        $this->attachPerTxnMetrics($transaksi);
        $summary = $this->computeSummary($transaksi);
        $periode = $start->format('Y-m-d') . ' s/d ' . $end->format('Y-m-d');

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Mingguan',
            'periode'   => $periode,
            'transaksi' => $transaksi,
            'summary'   => $summary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-mingguan-'.$start->format('Y-m-d').'.pdf');
    }

    public function bulanan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $bulan = $request->input('bulan') ?: now($tz)->format('Y-m');
        [$tahun, $bulanNum] = explode('-', $bulan);

        $start = Carbon::createFromDate((int)$tahun, (int)$bulanNum, 1, $tz)->startOfMonth();
        $end   = $start->copy()->endOfMonth();
        [$startQ, $endQ] = $this->rangeForQuery($start, $end);

        $transaksi = Transaction::with(['user','paymentMethod','items.product'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')->get();

        $this->attachPerTxnMetrics($transaksi);
        $summary = $this->computeSummary($transaksi);

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Bulanan',
            'periode'   => $bulan,
            'transaksi' => $transaksi,
            'summary'   => $summary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-bulanan-{$bulan}.pdf");
    }

    public function tahunan(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $tahun = (int) ($request->input('tahun') ?: now($tz)->year);

        $start = Carbon::create($tahun, 1, 1, 0, 0, 0, $tz)->startOfDay();
        $end   = Carbon::create($tahun,12,31,23,59,59,$tz)->endOfDay();
        [$startQ, $endQ] = $this->rangeForQuery($start, $end);

        $transaksi = Transaction::with(['user','paymentMethod','items.product'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')->get();

        $this->attachPerTxnMetrics($transaksi);
        $summary = $this->computeSummary($transaksi);

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Tahunan',
            'periode'   => (string)$tahun,
            'transaksi' => $transaksi,
            'summary'   => $summary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-tahunan-{$tahun}.pdf");
    }

    // ================== PREVIEW (HTML tabel) ==================

    public function previewHarian(Request $r){
        try {
            $tz = 'Asia/Jakarta';
            $tgl = $r->input('tanggal', now($tz)->toDateString());
            $start = Carbon::parse($tgl, $tz)->startOfDay();
            $end   = Carbon::parse($tgl, $tz)->endOfDay();
            [$startQ, $endQ] = $this->rangeForQuery($start, $end);

            $data = Transaction::with(['user','paymentMethod','items.product'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            $this->attachPerTxnMetrics($data);
            $summary = $this->computeSummary($data);

            return view('laporan._tabel', compact('data','summary'));
        } catch (\Throwable $e) {
            return response('<div class="p-6 text-red-600">Error: '.e($e->getMessage()).'</div>', 500);
        }
    }

    public function previewMingguan(Request $r){
        try {
            $tz = 'Asia/Jakarta';
            $week = $r->input('minggu', now($tz)->format('o-\WW'));
            if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $m)) {
                [$all,$y,$w] = $m;
                $start = Carbon::now($tz)->setISODate((int)$y,(int)$w)->startOfWeek(Carbon::MONDAY);
                $end   = (clone $start)->endOfWeek(Carbon::SUNDAY);
            } else {
                $start = now($tz)->startOfWeek(Carbon::MONDAY);
                $end   = now($tz)->endOfWeek(Carbon::SUNDAY);
            }
            [$startQ, $endQ] = $this->rangeForQuery($start, $end);

            $data = Transaction::with(['user','paymentMethod','items.product'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            $this->attachPerTxnMetrics($data);
            $summary = $this->computeSummary($data);

            return view('laporan._tabel', compact('data','summary'));
        } catch (\Throwable $e) {
            return response('<div class="p-6 text-red-600">Error: '.e($e->getMessage()).'</div>', 500);
        }
    }

    public function previewBulanan(Request $r){
        try {
            $tz = 'Asia/Jakarta';
            [$y,$m] = explode('-', $r->input('bulan', now($tz)->format('Y-m')));
            $start = Carbon::createFromDate((int)$y,(int)$m,1,$tz)->startOfMonth();
            $end   = $start->copy()->endOfMonth();
            [$startQ, $endQ] = $this->rangeForQuery($start, $end);

            $data = Transaction::with(['user','paymentMethod','items.product'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            $this->attachPerTxnMetrics($data);
            $summary = $this->computeSummary($data);

            return view('laporan._tabel', compact('data','summary'));
        } catch (\Throwable $e) {
            return response('<div class="p-6 text-red-600">Error: '.e($e->getMessage()).'</div>', 500);
        }
    }

    public function previewTahunan(Request $r){
        try {
            $tz = 'Asia/Jakarta';
            $y = (int) $r->input('tahun', now($tz)->year);
            $start = Carbon::create($y,1,1,0,0,0,$tz)->startOfDay();
            $end   = Carbon::create($y,12,31,23,59,59,$tz)->endOfDay();
            [$startQ, $endQ] = $this->rangeForQuery($start, $end);

            $data = Transaction::with(['user','paymentMethod','items.product'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            $this->attachPerTxnMetrics($data);
            $summary = $this->computeSummary($data);

            return view('laporan._tabel', compact('data','summary'));
        } catch (\Throwable $e) {
            return response('<div class="p-6 text-red-600">Error: '.e($e->getMessage()).'</div>', 500);
        }
    }
}
