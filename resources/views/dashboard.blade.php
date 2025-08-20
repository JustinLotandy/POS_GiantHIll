{{-- resources/views/dashboard.blade.php --}}
@php
    // Ambil data low-stock langsung di view supaya langsung jalan
    try {
        $lowStocks = \App\Models\Product::orderBy('stock', 'asc')
            ->limit(10)
            ->get(['id_Produk', 'name', 'stock']);
        $lowStockUnder15 = $lowStocks->where('stock', '<', 15);
        $lowStockCount   = $lowStockUnder15->count();
    } catch (\Throwable $e) {
        $lowStocks = collect([]);
        $lowStockUnder15 = collect([]);
        $lowStockCount = 0;
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4 md:px-10">
        {{-- Fitur utama POS --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
            <a href="{{ route('pos.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-2xl p-6 shadow flex flex-col items-center hover:scale-105 transition hover:ring-4 ring-blue-300 focus:outline-none">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h18v4H3V3zm0 6h18v13H3V9z" /></svg>
                <span class="font-bold">Point of Sales</span>
                <span class="text-xs">Transaksi Cepat</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="bg-gradient-to-br from-green-400 to-green-600 text-white rounded-2xl p-6 shadow flex flex-col items-center hover:scale-105 transition hover:ring-4 ring-green-300 focus:outline-none">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 6v6h6" /></svg>
                <span class="font-bold">Laporan</span>
                <span class="text-xs">Monitoring Omzet</span>
            </a>
            <a href="{{ route('products.index') }}" class="bg-gradient-to-br from-orange-400 to-orange-600 text-white rounded-2xl p-6 shadow flex flex-col items-center hover:scale-105 transition hover:ring-4 ring-orange-300 focus:outline-none">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19V6h13" /></svg>
                <span class="font-bold">Produk</span>
                <span class="text-xs">Kelola Barang</span>
            </a>
            <a href="{{ route('categories.index') }}" class="bg-gradient-to-br from-purple-400 to-purple-600 text-white rounded-2xl p-6 shadow flex flex-col items-center hover:scale-105 transition hover:ring-4 ring-purple-300 focus:outline-none">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 8v13h13" /></svg>
                <span class="font-bold">Kategori</span>
                <span class="text-xs">Kelola Kategori</span>
            </a>
            <a href="{{ route('payment_methods.index') }}" class="bg-gradient-to-br from-pink-400 to-pink-600 text-white rounded-2xl p-6 shadow flex flex-col items-center hover:scale-105 transition hover:ring-4 ring-pink-300 focus:outline-none">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3h1" /></svg>
                <span class="font-bold">Metode Pembayaran</span>
                <span class="text-xs">Pengaturan Payment</span>
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow p-6 text-center hover:ring-2 ring-blue-400 transition">
                <div class="text-3xl font-extrabold mb-2">{{ $totalProduk }}</div>
                <div class="text-gray-500">Total Produk</div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center hover:ring-2 ring-purple-400 transition">
                <div class="text-3xl font-extrabold mb-2">{{ $totalKategori }}</div>
                <div class="text-gray-500">Total Kategori</div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center hover:ring-2 ring-orange-400 transition">
                <div class="text-3xl font-extrabold mb-2">{{ $totalTransaksiHariIni }}</div>
                <div class="text-gray-500">Transaksi Hari Ini</div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center hover:ring-2 ring-green-400 transition">
                <div class="text-3xl font-extrabold mb-2">Rp {{ number_format($totalOmzetHariIni,0,',','.') }}</div>
                <div class="text-gray-500">Omzet Hari Ini</div>
            </div>
        </div>

        {{-- Grafik omzet --}}
        <div class="bg-white rounded-xl shadow p-6 mb-10">
            <h3 class="font-semibold text-lg mb-3">Grafik Omzet 7 Hari Terakhir</h3>
            <canvas id="grafikOmzet" height="70"></canvas>
        </div>

        {{-- Stok Minimum --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-lg mb-4">Stok Minimum (5 Produk Terendah)</h3>
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="py-2">ID Produk</th>
                        <th class="py-2">Nama</th>
                        <th class="py-2">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produkStokMinimum as $produk)
                        <tr>
                            <td class="py-1">{{ $produk->id_Produk }}</td>
                            <td class="py-1">{{ $produk->name }}</td>
                            <td class="py-1">{{ $produk->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const ctx = document.getElementById('grafikOmzet').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Omzet (Rp)',
                        data: {!! json_encode($dataOmzet) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
                        }
                    }
                }
            });
        })();
    </script>

    {{-- ===== TOAST LOW STOCK: pure JS, muncul 10 detik ===== --}}
    <div id="lowstock-toast"
         class="fixed top-16 right-5 z-[1000] w-96 max-w-[95vw] bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <div class="font-semibold text-gray-800">Notifikasi Stok Rendah</div>
            <button id="lowstock-close" class="text-gray-500 hover:text-gray-700" title="Tutup">✕</button>
        </div>
        <div class="px-4 py-3">
            @if($lowStockCount > 0)
                <div class="text-sm text-gray-700 mb-2">
                    {{ $lowStockCount }} produk stok &lt; 15. Beberapa di antaranya:
                </div>
                <ul class="text-sm text-gray-700 space-y-1 max-h-44 overflow-auto">
                    @foreach($lowStockUnder15 as $p)
                        <li class="flex justify-between">
                            <span>{{ $p->name }}</span>
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                                @if($p->stock <= 0) bg-red-600 text-white
                                @elseif($p->stock < 5) bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                Stok: {{ $p->stock }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-700">
                    Semua aman 🎉 — tidak ada stok &lt; 15.
                </div>
            @endif

            <div class="mt-3 text-[11px] text-gray-500">
                Notifikasi ini akan tertutup otomatis dalam 10 detik.
            </div>
        </div>
    </div>

    <script>
        (function () {
            const toast = document.getElementById('lowstock-toast');
            const closeBtn = document.getElementById('lowstock-close');

            // Tampilkan (pastikan terlihat meski Tailwind belum load penuh)
            toast.classList.remove('hidden');
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            toast.style.transition = 'opacity .25s ease, transform .25s ease';

            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            // Auto close 10 detik
            const timer = setTimeout(hide, 10000);

            // Close manual
            closeBtn.addEventListener('click', hide);

            function hide() {
                clearTimeout(timer);
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
                setTimeout(() => { toast.remove(); }, 250);
            }
        })();
    </script>
</x-app-layout>
