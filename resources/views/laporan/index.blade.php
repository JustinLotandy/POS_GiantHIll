<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-wide">
            Cetak Laporan Transaksi
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
            <h3 class="text-lg font-bold text-gray-700 mb-2">Pilih Jenis Laporan</h3>
            
            {{-- Laporan Harian --}}
            <form action="{{ route('laporan.harian') }}" method="GET" target="_blank" class="flex items-end gap-4 flex-wrap">
                <div>
                    <label for="tgl" class="block text-sm font-semibold text-gray-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tgl"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300 outline-none"
                           value="{{ now()->toDateString() }}">
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white font-semibold rounded-lg px-6 py-2 shadow flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M8 16h8M8 12h8m-6 8h6m-6-4h6m-2-14h-6m4 4v12a2 2 0 002 2h4a2 2 0 002-2V6a2 2 0 00-2-2h-4a2 2 0 00-2 2zm0 0H6a2 2 0 00-2 2v12a2 2 0 002 2h4"></path></svg>
                    Cetak Harian (PDF)
                </button>
            </form>

            {{-- Laporan Mingguan --}}
            <form action="{{ route('laporan.mingguan') }}" method="GET" target="_blank" class="flex items-end gap-4 flex-wrap">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 transition text-white font-semibold rounded-lg px-6 py-2 shadow flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M3 7h18M3 12h18M3 17h18"></path></svg>
                    Cetak Mingguan (PDF)
                </button>
                <span class="text-xs text-gray-400">(Otomatis 1 minggu terakhir)</span>
            </form>

            {{-- Laporan Bulanan --}}
            <form action="{{ route('laporan.bulanan') }}" method="GET" target="_blank" class="flex items-end gap-4 flex-wrap">
                <div>
                    <label for="bulan" class="block text-sm font-semibold text-gray-600 mb-1">Bulan</label>
                    <input type="month" name="bulan" id="bulan"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-300 outline-none"
                           value="{{ now()->format('Y-m') }}">
                </div>
                <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 transition text-white font-semibold rounded-lg px-6 py-2 shadow flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M4 4h16v16H4z"></path></svg>
                    Cetak Bulanan (PDF)
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
