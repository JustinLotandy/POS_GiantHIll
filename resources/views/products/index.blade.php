<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Produk
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <a href="{{ route('products.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">
           + Tambah Produk
        </a>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">ID Produk</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Nama</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Kategori</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Harga Sebelum</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Harga Sesudah</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Stok</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Gambar</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Barcode</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($products as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 text-gray-700 font-mono">{{ $p->id_Produk }}</td>
                            <td class="px-4 py-2">{{ $p->name }}</td>
                            <td class="px-4 py-2">{{ $p->category->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-2 text-blue-800 font-semibold">Rp {{ number_format($p->harga_sebelum,0,',','.') }}</td>
                            <td class="px-4 py-2 text-green-700 font-semibold">Rp {{ number_format($p->harga_sesudah,0,',','.') }}</td>
                            <td class="px-4 py-2">{{ $p->stock }}</td>
                            <td class="px-4 py-2">
                                @if($p->image)
                                    <img src="{{ asset('storage/'.$p->image) }}" class="rounded w-14 h-14 object-cover shadow border" alt="{{ $p->name }}">
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 font-mono">{{ $p->barcode }}</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="{{ route('products.edit', $p->id_Produk) }}"
                                   class="inline-block px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-xs rounded font-semibold text-white shadow">
                                   Edit
                                </a>
                                <form action="{{ route('products.destroy', $p->id_Produk) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-xs rounded font-semibold text-white shadow" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500 italic">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        {{-- ==================== POPUP LOW STOCK (TANPA TIMER) ==================== --}}
    @if($lowStockCount > 0)
    <div id="lowstock-overlay"
         class="fixed inset-0 z-[1000] bg-black/60 flex items-center justify-center"
         role="dialog" aria-modal="true" aria-label="Notifikasi stok rendah">
        <!-- Card -->
        <div id="lowstock-card"
             class="relative w-[92vw] max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">
            <!-- Header -->
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="font-semibold text-gray-800 dark:text-gray-100">
                    ⚠️ Stok Kurang dari 15
                </div>
                <button id="lowstock-btn-close"
                        class="rounded-md px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200"
                        title="Tutup pop up">
                    Tutup
                </button>
            </div>

            <!-- Body -->
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                    Berikut daftar produk dengan stok rendah. Klik di area gelap mana saja untuk menutup.
                </p>

                <div class="max-h-72 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-white dark:bg-gray-800">
                            <tr class="text-gray-500">
                                <th class="text-left py-2 pr-2">Produk</th>
                                <th class="text-left py-2 pr-2">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($lowStocks as $p)
                                <tr>
                                    <td class="py-2 pr-2 text-gray-800 dark:text-gray-100">{{ $p->name }}</td>
                                    <td class="py-2 pr-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            @if($p->stock <= 0) bg-red-600 text-white
                                            @elseif($p->stock < 5) bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                            @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 @endif">
                                            {{ $p->stock }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-[11px] text-gray-500 dark:text-gray-400">
                    *Tekan ESC atau klik area gelap untuk menutup.
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        (function () {
            const overlay = document.getElementById('lowstock-overlay');     // mungkin null jika tidak ada stok rendah
            const card    = document.getElementById('lowstock-card');
            const btnClose= document.getElementById('lowstock-btn-close');
            const openBtn = document.getElementById('btn-open-lowstock');
            const canOpen = {{ $lowStockCount > 0 ? 'true' : 'false' }};

            function closePopup() {
                if (!overlay) return;
                overlay.style.opacity = '1';
                overlay.style.transition = 'opacity .2s ease';
                requestAnimationFrame(() => {
                    overlay.style.opacity = '0';
                    setTimeout(() => { overlay.style.display = 'none'; }, 180);
                });
            }

            function openPopup() {
                if (!canOpen) return;
                // Jika overlay sudah di-hide, tampilkan lagi
                const el = document.getElementById('lowstock-overlay');
                if (el) {
                    el.style.display = 'flex';
                    requestAnimationFrame(() => {
                        el.style.opacity = '1';
                    });
                }
            }

            // Klik area gelap (overlay) untuk menutup — tapi hindari klik di dalam card
            if (overlay && card) {
                overlay.addEventListener('click', (e) => {
                    // hanya tutup bila klik tepat pada overlay (bukan anaknya/card)
                    if (e.target === overlay) {
                        closePopup();
                    }
                });
            }

            // Tombol Tutup
            btnClose?.addEventListener('click', closePopup);

            // Tombol header "Notifikasi Stok" untuk buka lagi setelah ditutup
            openBtn?.addEventListener('click', openPopup);

            // ESC untuk tutup
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && overlay && overlay.style.display !== 'none') {
                    closePopup();
                }
            });
        })();
    </script>
    {{-- ==================== /POPUP LOW STOCK ==================== --}}
</x-app-layout>
