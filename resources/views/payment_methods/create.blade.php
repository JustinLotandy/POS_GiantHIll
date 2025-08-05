<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Metode Pembayaran
        </h2>
    </x-slot>
    <div class="max-w-lg mx-auto py-8">
        <form action="{{ route('payment_methods.store') }}" method="POST" class="bg-white rounded shadow p-6 space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-semibold">ID Metode (boleh manual/UUID)</label>
                <input type="text" name="id_PaymentMethod" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('id_PaymentMethod') }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Nama Metode</label>
                <input type="text" name="name_payment" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('name_payment') }}" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
                <a href="{{ route('payment_methods.index') }}" class="ml-2 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
