<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Transaksi</h2>
    </x-slot>
    <div class="max-w-lg mx-auto py-8">
        <form action="{{ route('transactions.update', $transaction->id_Transaction) }}" method="POST" class="bg-white rounded shadow p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1 font-semibold">ID Transaksi</label>
                <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" value="{{ $transaction->id_Transaction }}" readonly>
            </div>
            <div>
                <label class="block mb-1 font-semibold">User</label>
                <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" value="{{ $transaction->user->name ?? '-' }}" readonly>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Metode Pembayaran</label>
                <select name="id_PaymentMethod" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Pilih Metode</option>
                    @foreach($paymentMethods as $pm)
                        <option value="{{ $pm->id_PaymentMethod }}" {{ $transaction->id_PaymentMethod == $pm->id_PaymentMethod ? 'selected' : '' }}>
                            {{ $pm->name_payment }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Total</label>
                <input type="number" step="0.01" name="total" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('total', $transaction->total) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Dibayar</label>
                <input type="number" step="0.01" name="paid" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('paid', $transaction->paid) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Kembalian</label>
                <input type="number" step="0.01" name="change" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('change', $transaction->change) }}" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update</button>
            <a href="{{ route('transactions.index') }}" class="ml-2 text-gray-700">Batal</a>
        </form>
    </div>
</x-app-layout>
