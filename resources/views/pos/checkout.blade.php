<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout</h2>
    </x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 bg-white rounded-xl shadow">
        <h3 class="text-lg font-bold mb-4">Pesanan</h3>
        <table class="w-full mb-6">
            <thead>
                <tr>
                    <th class="py-2 text-left">Nama</th>
                    <th class="py-2 text-center">Qty</th>
                    <th class="py-2 text-right">Harga</th>
                    <th class="py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($cart as $item)
                    @php $subtotal = $item['qty'] * $item['harga_sesudah']; $total += $subtotal; @endphp
                    <tr>
                        <td class="py-1">{{ $item['name'] }}</td>
                        <td class="py-1 text-center">{{ $item['qty'] }}</td>
                        <td class="py-1 text-right">Rp {{ number_format($item['harga_sesudah'], 0, ',', '.') }}</td>
                        <td class="py-1 text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td colspan="3" class="text-right py-2">Total</td>
                    <td class="text-right py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Form pembayaran -->
        <form action="{{ route('pos.checkout') }}" method="POST">
            @csrf
            
             <div class="mb-4">
                <label for="id_PaymentMethod" class="block font-semibold mb-1">Metode Pembayaran</label>
                <select name="id_PaymentMethod" id="id_PaymentMethod" class="border rounded px-3 py-2 w-full" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    @foreach($paymentMethods as $pm)
                        <option value="{{ $pm->id_PaymentMethod }}">{{ $pm->name_payment }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="bayar" class="block font-semibold mb-1">Jumlah Bayar</label>
                <input type="number" id="bayar" name="bayar" class="border rounded px-3 py-2 w-full" min="0" required placeholder="Masukkan jumlah bayar" oninput="hitungKembalian()">
            </div>
            <div class="mb-4">
                <label for="kembalian" class="block font-semibold mb-1">Kembalian</label>
                <input type="text" id="kembalian" class="border rounded px-3 py-2 w-full bg-gray-100" readonly>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700 transition">
                Simpan & Cetak Struk
            </button>
        </form>
    </div>

    <script>
        function hitungKembalian() {
            const total = {{ $total }};
            const bayar = parseInt(document.getElementById('bayar').value) || 0;
            const kembalian = bayar - total;
            document.getElementById('kembalian').value = 'Rp ' + (kembalian > 0 ? kembalian.toLocaleString('id-ID') : '0');
        }
    </script>
</x-app-layout>
