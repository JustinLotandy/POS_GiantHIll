<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kategori
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white rounded shadow p-8 mt-8">
        <form action="{{ route('categories.update', $category->id_kategori) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1 font-semibold">ID Kategori</label>
                <input type="text" name="id_kategori" value="{{ old('id_kategori', $category->id_kategori) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" readonly>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('categories.index') }}" class="text-gray-600 hover:underline">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
