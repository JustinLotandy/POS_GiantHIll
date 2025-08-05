<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Produk
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded shadow p-8 mt-8">
        <form action="{{ route('products.update', $product->id_Produk) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-semibold">ID Produk</label>
                <input type="text" name="id_Produk" value="{{ old('id_Produk', $product->id_Produk) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nama Produk</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('name', $product->name) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Kategori</label>
                <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_kategori }}" {{ $product->category_id == $cat->id_kategori ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block mb-1 font-semibold">Harga Sebelum</label>
                    <input type="number" step="0.01" name="harga_sebelum" class="w-full border border-gray-300 rounded px-3 py-2"
                           value="{{ old('harga_sebelum', $product->harga_sebelum) }}" required>
                </div>
                <div class="w-1/2">
                    <label class="block mb-1 font-semibold">Harga Sesudah</label>
                    <input type="number" step="0.01" name="harga_sesudah" class="w-full border border-gray-300 rounded px-3 py-2"
                           value="{{ old('harga_sesudah', $product->harga_sesudah) }}" required>
                </div>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Stok</label>
                <input type="number" name="stock" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('stock', $product->stock) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Gambar Saat Ini</label><br>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded object-cover mb-2 border shadow" alt="Produk">
                @else
                    <span class="text-gray-500 italic">Tidak ada gambar</span><br>
                @endif
                <label class="block mb-1 font-semibold">Ubah Gambar (optional)</label>
                <input type="file" name="image" class="w-full border border-gray-300 rounded px-3 py-2 mt-1">
            </div>
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
