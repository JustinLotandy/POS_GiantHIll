<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Produk
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Tombol Tambah Produk -->
        <a href="{{ route('products.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">
           + Tambah Produk
        </a>

        <!-- Tombol Cetak PDF -->
        <a href="{{ route('products.pdf') }}"
           class="px-4 py-2  bg-gray-800 text-white hover:bg-black mb-4">Cetak PDF
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
                            <td class="px-4 py-2 text-blue-800 font-semibold">
                                Rp {{ number_format($p->harga_sebelum,0,',','.') }}
                            </td>
                            <td class="px-4 py-2 text-green-700 font-semibold">
                                Rp {{ number_format($p->harga_sesudah,0,',','.') }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $p->stock }}

                                {{-- Label stok dinamis --}}
                                @if($p->stock == 0)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-red-700 text-white rounded-full">
                                        Habis
                                    </span>
                                @elseif($p->stock < 10)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-red-500 text-white rounded-full">
                                        Sangat Rendah
                                    </span>
                                @elseif($p->stock < 15)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-yellow-400 text-black rounded-full">
                                        Low Stock
                                    </span>
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
</x-app-layout>
