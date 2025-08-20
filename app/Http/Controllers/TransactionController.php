{{-- resources/views/pos/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Point of Sale
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 relative">
                        @if($product->stock <= 0)
                            <div class="absolute top-2 left-2 right-2 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded">
                                HABIS — TIDAK BISA DIJUAL
                            </div>
                        @endif

                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-28 object-cover rounded">

                        <div class="mt-3 font-bold text-gray-800 dark:text-gray-200">
                            {{ $product->name }}
                        </div>
                        <div class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            Stok: {{ $product->stock }}
                        </div>
                        <div class="text-orange-600 font-bold">
                            Rp {{ number_format($product->harga_sesudah,0,',','.') }}
                        </div>

                        @if($product->stock > 0)
                            <form method="POST" action="{{ route('pos.addToCart', $product->id_Produk) }}">
                                @csrf
                                <button type="submit"
                                        class="mt-2 w-full text-center bg-blue-600 text-white py-1 rounded hover:bg-blue-700">
                                    Tambah +
                                </button>
                            </form>
                        @else
                            <button type="button"
                                    onclick="openEmptyPopup()"
                                    class="mt-2 w-full text-center bg-gray-400 text-white py-1 rounded cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- === POPUP STOCK ERROR DARI SESSION === --}}
    @if(session('stock_error'))
      <div id="stock-popup"
           class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60">
        <div class="w-[92vw] max-w-md bg-white rounded-xl shadow-2xl border border-gray-200">
          <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
            <div class="font-semibold text-gray-800">Stok Tidak Tersedia</div>
            <button onclick="document.getElementById('stock-popup').remove()"
                    class="px-3 py-1.5 text-sm rounded bg-gray-100 hover:bg-gray-200">
              Tutup
            </button>
          </div>
          <div class="px-5 py-4 text-sm text-gray-700">
            {{ session('stock_error') }}
          </div>
        </div>
      </div>
    @endif

    {{-- === TEMPLATE POPUP UNTUK STOK HABIS === --}}
    <div id="stock-empty-template" class="hidden">
      <div class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60">
        <div class="w-[92vw] max-w-md bg-white rounded-xl shadow-2xl border border-gray-200">
          <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
            <div class="font-semibold text-gray-800">Stok Habis</div>
            <button class="btn-popup-close px-3 py-1.5 text-sm rounded bg-gray-100 hover:bg-gray-200">
              Tutup
            </button>
          </div>
          <div class="px-5 py-4 text-sm text-gray-700">
            Stok produk ini sudah <b>HABIS</b>, tidak bisa ditambahkan ke keranjang.
          </div>
        </div>
      </div>
    </div>

    <script>
      function openEmptyPopup() {
        const tpl = document.getElementById('stock-empty-template');
        if (!tpl) return;
        const wrapper = tpl.firstElementChild.cloneNode(true);

        // close by button
        wrapper.querySelector('.btn-popup-close').addEventListener('click', () => {
            wrapper.remove();
        });
        // close by clicking overlay
        wrapper.addEventListener('click', (e) => {
            if (e.target === wrapper) wrapper.remove();
        });

        document.body.appendChild(wrapper);
      }
    </script>
</x-app-layout>
