<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Transaksi</h2>
    </x-slot>
    <div class="max-w-lg mx-auto py-8">
        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded shadow p-6 space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-semibold">User ID</label>
                <input type="number" name="user_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Metode Pembayaran</label>
                <select name="id_PaymentMethod" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">Pilih Metode</option>
                    @foreach($paymentMethods as $pm)
                        <option value="{{ $pm->id_PaymentMethod }}">{{ $pm->name_payment }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Total</label>
                <input type="number" step="0.01" name="total" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Dibayar</label>
                <input type="number" step="0.01" name="paid" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Kembalian</label>
                <input type="number" step="0.01" name="change" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
            <a href="{{ route('transactions.index') }}" class="ml-2 text-gray-700">Batal</a>
        </form>
    </div>
</x-app-layout>
