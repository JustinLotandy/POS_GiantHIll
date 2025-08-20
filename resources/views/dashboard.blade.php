{{-- resources/views/dashboard.blade.php --}}
@php
    // Ambil data low stock langsung di view agar langsung berfungsi
    try {
        $lowStocks = \App\Models\Product::where('stock', '<', 15)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get(['id_Produk', 'name', 'stock']);
        $lowStockCount = $lowStocks->count();
    } catch (\Throwable $e) {
        $lowStocks = collect([]);
        $lowStockCount = 0;
    }
@endphp

{{-- Alpine.js untuk animasi/timer (aman jika sudah dimuat di layout—boleh tetap dibiarkan) --}}
<script src="https://unpkg.com/alpinejs" defer></script>
<style>[x-cloak]{ display:none !important; }</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- ====== Konten dashboard kamu di sini ====== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 dark:text-gray-100 font-semibold">Selamat datang 👋</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Ini adalah beranda dashboard.</div>
                </div>
                {{-- ... kartu lain kalau ada ... --}}
            </div>
        </div>
    </div>

    {{-- ====== TOAST LOW STOCK: MUNCUL 10 DETIK DI KANAN ATAS ====== --}}
    @if($lowStockCount > 0)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 10000)"  {{-- 10.000 ms = 10 detik --}}
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="fixed top-16 right-5 z-[1000] w-96 max-w-[95vw] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg"
        role="status"
        aria-live="polite"
    >
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="font-semibold text-gray-800 dark:text-gray-100">
                Notifikasi Stok Rendah
            </div>
            <button
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                @click="show=false"
                aria-label="Tutup notifikasi"
                title="Tutup"
            >✕</button>
        </div>

        <div class="px-4 py-3">
            <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                {{ $lowStockCount }} produk stok &lt; 15. Beberapa di antaranya:
            </div>

            <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 max-h-44 overflow-auto">
                @foreach($lowStocks as $p)
                    <li class="flex justify-between">
                        <span>{{ $p->name }}</span>
                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full
                            @if($p->stock <= 0) bg-red-600 text-white
                            @elseif($p->stock < 5) bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                            @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 @endif">
                            Stok: {{ $p->stock }}
                        </span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-3 text-[11px] text-gray-500 dark:text-gray-400">
                Notifikasi ini akan tertutup otomatis dalam 10 detik.
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
