<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Transaksi</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <a href="{{ route('pos.index') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">
           + Tambah Transaksi
        </a>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-center">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Dibayar</th>
                        <th>Kembalian</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $trx)
                        <tr>
                            <td>{{ $trx->id_Transaction }}</td>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td>{{ $trx->paymentMethod->name_payment ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
                            <td>Rp {{ number_format($trx->paid,0,',','.') }}</td>
                            <td>Rp {{ number_format($trx->change,0,',','.') }}</td>
                            <td>{{ $trx->created_at }}</td>
                            <td class="flex gap-1 justify-center">
                                {{-- Tombol Edit (jika kamu punya fitur edit transaksi) --}}
                                <a href="{{ route('transactions.edit', $trx->id_Transaction) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('transactions.destroy', $trx->id_Transaction) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
