<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Metode Pembayaran
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <a href="{{ route('payment_methods.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">
           + Tambah Metode
        </a>
        <div class="bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 font-bold text-gray-700">ID</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Nama Metode</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-mono">{{ $pay->id_PaymentMethod }}</td>
                            <td class="px-4 py-2">{{ $pay->name_payment }}</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="{{ route('payment_methods.edit', $pay->id_PaymentMethod) }}"
                                   class="px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-xs rounded font-semibold text-white shadow">
                                   Edit
                                </a>
                                <form action="{{ route('payment_methods.destroy', $pay->id_PaymentMethod) }}" method="POST" class="inline"
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
                                Belum ada data metode pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
