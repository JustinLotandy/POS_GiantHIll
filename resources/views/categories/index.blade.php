<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Kategori
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <a href="{{ route('categories.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4">
           + Tambah Kategori
        </a>
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">ID Kategori</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Nama Kategori</th>
                        <th class="px-4 py-3 font-bold text-gray-700 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($categories as $c)
                        <tr>
                            <td class="px-4 py-2 text-gray-700 font-mono">{{ $c->id_kategori }}</td>
                            <td class="px-4 py-2">{{ $c->nama_kategori }}</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="{{ route('categories.edit', $c->id_kategori) }}"
                                   class="inline-block px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-xs rounded font-semibold text-white shadow">
                                   Edit
                                </a>
                                <form action="{{ route('categories.destroy', $c->id_kategori) }}" method="POST" class="inline"
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
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500 italic">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
