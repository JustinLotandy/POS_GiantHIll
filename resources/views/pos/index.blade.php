<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Point of Sales</h2>
    </x-slot>
    <div class="bg-gray-100 min-h-screen flex flex-col md:flex-row gap-6 p-6">
        <!-- Produk Grid -->
        <div class="w-full md:w-3/5">
            <form action="{{ route('pos.cariBarcode') }}" method="GET">
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
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8" style="margin-top: 20px;">
                @foreach ($products as $product)
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border hover:shadow-lg transition">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) }}"
                             class="w-20 h-20 object-cover rounded-lg mb-2 border shadow"
                             alt="{{ $product->name }}">
                        <div class="text-gray-800 font-bold text-base text-center mb-1">{{ $product->name }}</div>
                        <div class="text-gray-500 text-xs mb-1">Stok: <b>{{ $product->stock }}</b></div>
                        <div class="text-orange-600 font-bold text-lg mb-2">Rp {{ number_format($product->harga_sesudah, 0, ',', '.') }}</div>
                        <form action="{{ route('pos.add') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id_Produk }}">
                            <button type="submit" class="w-full bg-blue-600 text-white py-1 rounded hover:bg-blue-700 transition">
                                Tambah +
                            </button>
                        </form>
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
                        {{-- debug --}}
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

                <!-- <script>
                const input = document.getElementById('scan-barcode');
                const form  = document.getElementById('form-scan');
                input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === 'Tab') {
                    e.preventDefault();
                    if (input.value.trim() !== '') form.submit();
                }
                });
                </script> -->
            </div>
        </div>
    </div>
</x-app-layout>
