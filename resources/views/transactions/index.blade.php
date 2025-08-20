<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Transaksi</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <a href="{{ route('pos.index') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">
           + Tambah Transaksi
        </a>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr class="text-center">
                        <th class="px-3 py-3">ID</th>
                        <th class="px-3 py-3">User</th>
                        <th class="px-3 py-3">Metode</th>
                        <th class="px-3 py-3 text-left">Produk</th>
                        <th class="px-3 py-3">Total</th>
                        <th class="px-3 py-3">Dibayar</th>
                        <th class="px-3 py-3">Kembalian</th>
                        <th class="px-3 py-3">Tanggal</th>
                        <th class="px-3 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $trx)
                        <tr class="text-center align-top">
                            <td class="px-3 py-3 font-mono">{{ $trx->id_Transaction }}</td>
                            <td class="px-3 py-3">{{ $trx->user->name ?? '-' }}</td>
                            <td class="px-3 py-3">{{ $trx->paymentMethod->name_payment ?? '-' }}</td>

                            {{-- Produk dalam transaksi --}}
                            <td class="px-3 py-3 text-left">
                                @if($trx->items && $trx->items->count())
                                    <ul class="flex flex-wrap gap-2">
                                        @foreach($trx->items as $item)
                                            <li class="inline-flex items-center gap-2 bg-gray-100 px-2 py-1 rounded">
                                                <span class="text-gray-800">
                                                    {{ $item->product->name ?? $item->nama_produk ?? 'Produk dihapus' }}
                                                </span>
                                                <span class="text-xs bg-gray-200 px-1.5 py-0.5 rounded">
                                                    x {{ $item->quantity }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>

                            <td class="px-3 py-3">Rp {{ number_format($trx->total,0,',','.') }}</td>
                            <td class="px-3 py-3">Rp {{ number_format($trx->paid,0,',','.') }}</td>
                            <td class="px-3 py-3">Rp {{ number_format($trx->change,0,',','.') }}</td>
                            <td class="px-3 py-3">{{ $trx->created_at }}</td>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-2 justify-center">
                                    <a href="{{ route('transactions.edit', $trx->id_Transaction) }}"
                                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</a>
                                    <form action="{{ route('transactions.destroy', $trx->id_Transaction) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
