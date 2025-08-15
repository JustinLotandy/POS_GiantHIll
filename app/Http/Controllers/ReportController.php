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
        // Halaman & cetak dijaga permission
        $this->middleware('permission:laporan.harian')->only(['index','harian']);
        $this->middleware('permission:laporan.mingguan')->only('mingguan');
        $this->middleware('permission:laporan.bulanan')->only('bulanan');
        $this->middleware('permission:laporan.tahunan')->only('tahunan');
        // Preview TIDAK diproteksi permission agar gampang dites (tetap lewat auth dari group)
    }

    public function index()
    {
        return view('laporan.index');
    }

    // ===== Helpers =====
    private function useUtc(): bool
    {
        // .env: REPORT_USE_UTC=true|false  (default: false)
        return filter_var(config('app.report_use_utc', env('REPORT_USE_UTC', false)), FILTER_VALIDATE_BOOLEAN);
    }

    /** Kembalikan [start, end] untuk whereBetween sesuai mode (UTC / lokal) */
    private function rangeForQuery(Carbon $startLocal, Carbon $endLocal): array
    {
        if ($this->useUtc()) {
            return [$startLocal->copy()->timezone('UTC'), $endLocal->copy()->timezone('UTC')];
        }
        return [$startLocal, $endLocal];
    }

    // ===== CETAK PDF =====
    public function harian(Request $request)
    {
        $tz = 'Asia/Jakarta';
        $tanggal = $request->input('tanggal') ?: now($tz)->toDateString();

        $start = Carbon::parse($tanggal, $tz)->startOfDay();
        $end   = Carbon::parse($tanggal, $tz)->endOfDay();
        [$startQ, $endQ] = $this->rangeForQuery($start, $end);

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Harian',
            'periode'   => $tanggal,
            'transaksi' => $transaksi,
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

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')
            ->get();

        $periode = $start->format('Y-m-d') . ' s/d ' . $end->format('Y-m-d');

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Mingguan',
            'periode'   => $periode,
            'transaksi' => $transaksi,
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

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Bulanan',
            'periode'   => $bulan,
            'transaksi' => $transaksi,
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

        $transaksi = Transaction::with(['user','paymentMethod'])
            ->whereBetween('created_at', [$startQ, $endQ])
            ->orderBy('created_at')
            ->get();

        $pdf = PDF::loadView('laporan.template', [
            'title'     => 'Laporan Tahunan',
            'periode'   => (string)$tahun,
            'transaksi' => $transaksi,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-tahunan-{$tahun}.pdf");
    }

    // ===== PREVIEW (HTML tabel) =====
    public function previewHarian(Request $r){
        try {
            $tz = 'Asia/Jakarta';
            $tgl = $r->input('tanggal', now($tz)->toDateString());
            $start = Carbon::parse($tgl, $tz)->startOfDay();
            $end   = Carbon::parse($tgl, $tz)->endOfDay();
            [$startQ, $endQ] = $this->rangeForQuery($start, $end);

            $data = Transaction::with(['user','paymentMethod'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            if (!view()->exists('laporan._tabel')) {
                return response('<div class="p-6 text-red-600">View <b>laporan._tabel</b> tidak ditemukan.</div>', 500);
            }

            \Log::info('previewHarian', ['tanggal'=>$tgl, 'count'=>$data->count()]);
            return view('laporan._tabel', compact('data'));
        } catch (\Throwable $e) {
            \Log::error('previewHarian error', ['msg'=>$e->getMessage()]);
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

            $data = Transaction::with(['user','paymentMethod'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            if (!view()->exists('laporan._tabel')) {
                return response('<div class="p-6 text-red-600">View <b>laporan._tabel</b> tidak ditemukan.</div>', 500);
            }

            \Log::info('previewMingguan', ['minggu'=>$week, 'count'=>$data->count()]);
            return view('laporan._tabel', compact('data'));
        } catch (\Throwable $e) {
            \Log::error('previewMingguan error', ['msg'=>$e->getMessage()]);
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

            $data = Transaction::with(['user','paymentMethod'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            if (!view()->exists('laporan._tabel')) {
                return response('<div class="p-6 text-red-600">View <b>laporan._tabel</b> tidak ditemukan.</div>', 500);
            }

            \Log::info('previewBulanan', ['bulan'=>"$y-$m", 'count'=>$data->count()]);
            return view('laporan._tabel', compact('data'));
        } catch (\Throwable $e) {
            \Log::error('previewBulanan error', ['msg'=>$e->getMessage()]);
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

            $data = Transaction::with(['user','paymentMethod'])
                ->whereBetween('created_at', [$startQ, $endQ])
                ->latest()->get();

            if (!view()->exists('laporan._tabel')) {
                return response('<div class="p-6 text-red-600">View <b>laporan._tabel</b> tidak ditemukan.</div>', 500);
            }

            \Log::info('previewTahunan', ['tahun'=>$y, 'count'=>$data->count()]);
            return view('laporan._tabel', compact('data'));
        } catch (\Throwable $e) {
            \Log::error('previewTahunan error', ['msg'=>$e->getMessage()]);
            return response('<div class="p-6 text-red-600">Error: '.e($e->getMessage()).'</div>', 500);
        }
    }
}
