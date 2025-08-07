<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kategori
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto bg-white rounded shadow p-8 mt-8">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-semibold">ID Kategori <span class="text-xs text-gray-400">(opsional/manual)</span></label>
                
                @if($lastId)
                    <p class="text-sm text-gray-500 mb-1">ID Sebelumnya: <span class="font-semibold">{{ $lastId }}</span></p>
                @endif

                <input type="text" name="id_kategori" maxlength="36"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                    value="{{ old('id_kategori', $suggestedId ?? '') }}"
                    required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('categories.index') }}" class="text-gray-600 hover:underline">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
