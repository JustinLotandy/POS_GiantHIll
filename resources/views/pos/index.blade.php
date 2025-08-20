<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Point of Sales</h2>
    </x-slot>

    {{-- POPUP: tampil kalau ada error stok dari server (misal coba tambah melebihi stok lewat barcode) --}}
    @if(session('stock_error'))
        <div id="stock-popup" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60">
            <div class="w-[92vw] max-w-md bg-white rounded-xl shadow-2xl border border-gray-200">
                <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                    <div class="font-semibold text-gray-800">Stok Tidak Tersedia</div>
                    <button onclick="document.getElementById('stock-popup').remove()" class="px-3 py-1.5 text-sm rounded bg-gray-100 hover:bg-gray-200">
                        Tutup
                    </button>
                </div>
                <div class="px-5 py-4 text-sm text-gray-700">
                    {{ session('stock_error') }}
                </div>
            </div>
        </div>
    @endif

    <div class="bg-gray-100 min-h-screen flex flex-col md:flex-row gap-6 p-6">

        <!-- Produk Grid -->
        <div class="w-full md:w-3/5" style="margin-bottom: 20px;">
            <form action="{{ route('pos.cariBarcode') }}" method="GET" class="flex gap-2">
                <input
                    id="scan-barcode"
                    name="barcode"
                    type="text"
                    autofocus
                    class="w-full md:w-2/3 px-4 py-2 rounded border border-gray-300 focus:outline-blue-500"
                    placeholder="Scan barcode produk atau cari nama/SKU..."
                >
                <button class="bg-blue-500 px-4 py-2 text-white rounded hover:bg-blue-600 transition">Scan</button>
            </form>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8 mt-5">
                @foreach ($products as $product)
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border hover:shadow-lg transition relative overflow-hidden">
                        {{-- Pita peringatan stok --}}
                        @if($product->stock <= 0)
                            <div class="absolute top-2 left-2 right-2">
                                <span class="block text-[11px] tracking-wide font-semibold uppercase bg-red-600 text-white px-3 py-1 rounded">
                                    Habis — Tidak Bisa Dijual
                                </span>
                            </div>
                        @elseif($product->stock < 15)
                            <div class="absolute top-2 left-2 right-2">
                                <span class="block text-[11px] tracking-wide font-semibold uppercase bg-yellow-500 text-black px-3 py-1 rounded">
                                    Stok Rendah
                                </span>
                            </div>
                        @endif

                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) }}"
                             class="w-20 h-20 object-cover rounded-lg mb-2 border shadow mt-6"
                             alt="{{ $product->name }}">

                        <div class="text-gray-800 font-bold text-base text-center mb-1">{{ $product->name }}</div>

                        <div class="text-xs mb-1
                            @if($product->stock <= 0) text-red-600
                            @elseif($product->stock < 15) text-yellow-600
                            @else text-gray-500 @endif">
                            Stok: <b>{{ $product->stock }}</b>
                        </div>

                        <div class="text-orange-600 font-bold text-lg mb-2">
                            Rp {{ number_format($product->harga_sesudah, 0, ',', '.') }}
                        </div>

                        {{-- ACTION: jika stok 0, tombol "Tambah +" disembunyikan total --}}
                        @if($product->stock > 0)
                            <form action="{{ route('pos.add') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id_Produk }}">
                                <button type="submit" class="w-full bg-blue-600 text-white py-1 rounded hover:bg-blue-700 transition">
                                    Tambah +
                                </button>
                            </form>
                        @else
                            {{-- Tidak render tombol apapun agar tulisan "Tambah +" tidak muncul --}}
                            <div class="w-full text-center mt-1 text-[12px] text-gray-400 select-none">
                                Tidak bisa ditambahkan
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar Keranjang -->
        <div class="w-full md:w-2/5 bg-white rounded-xl shadow p-6 flex flex-col justify-between border">
            <div>
                <h3 class="text-gray-800 font-bold text-lg mb-3">Keranjang</h3>
                @if (count($cart) > 0)
                    <ul>
                        @foreach ($cart as $item)
                            <li class="flex justify-between items-center mb-3 border-b border-gray-200 pb-2">
                                <div>
                                    <div class="text-gray-800 font-medium">
                                        {{ $item['name'] ?? '-' }}
                                    </div>
                                    <div class="text-gray-500 text-xs">
                                        {{ $item['qty'] ?? 0 }} x Rp {{ number_format($item['harga_sesudah'] ?? 0,0,',','.') }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('pos.updateQty') }}" method="POST" class="flex gap-1">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                                        <button type="submit" name="action" value="decrease"
                                                class="px-2 bg-gray-200 rounded text-orange-600 font-bold">-</button>
                                        <span class="px-2 text-gray-800">{{ $item['qty'] ?? 0 }}</span>
                                        <button type="submit" name="action" value="increase"
                                                class="px-2 bg-gray-200 rounded text-orange-600 font-bold">+</button>
                                    </form>
                                    <form action="{{ route('pos.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                                        <button class="ml-2 px-2 text-xs bg-red-500 hover:bg-red-600 text-white rounded" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-gray-400 text-center my-12">Keranjang kosong</div>
                @endif
            </div>

            <div>
                <div class="flex justify-between mt-4 text-gray-800">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format(array_sum(array_map(fn($i) => $i['qty'] * $i['harga_sesudah'], $cart)),0,',','.') }}</span>
                </div>
                <form action="{{ route('pos.checkout') }}" method="GET" class="mt-4">
                    @csrf
                    <button class="w-full py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-600 transition text-lg">
                        Proses Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</x-app-layout>
