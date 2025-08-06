<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Produk
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded shadow p-8 mt-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-semibold">ID Produk <span class="text-xs text-gray-400">(opsional/manual)</span></label>
                <input type="text" name="id_Produk" maxlength="36"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="Biarkan kosong untuk UUID otomatis">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nama Produk</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Kategori</label>
                <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_kategori }}">{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block mb-1 font-semibold">Harga Sebelum</label>
                    <input type="number" step="0.01" name="harga_sebelum" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="w-1/2">
                    <label class="block mb-1 font-semibold">Harga Sesudah</label>
                    <input type="number" step="0.01" name="harga_sesudah" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Stok</label>
                <input type="number" name="stock" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Gambar (optional)</label>
                <input type="file" name="image" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Barcode (optional)</label>
                <input type="text" name="barcode" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="flex justify-between items-center mt-4">
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">Kembali</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
            Simpan
        </button>
    </div>
</form>
</div>
</x-app-layout>
