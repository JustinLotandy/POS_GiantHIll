<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- pastikan Vite sudah aktif --}}
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <div class="flex p-6 gap-4">
        {{-- KOLOM KIRI: PRODUK --}}
        <div class="w-2/3 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
                <div class="bg-white text-black rounded p-4 shadow">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-32 object-cover">
                    @else
                        <div class="bg-gray-200 w-full h-32 flex items-center justify-center text-sm">No Image</div>
                    @endif
                    <p class="font-bold mt-2">{{ $product->name }}</p>
                    <p>IDR {{ number_format($product->price) }}</p>
                    <p class="text-xs text-gray-600">Stok: {{ $product->stock }}</p>
                    <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" class="bg-orange-500 text-white px-3 py-1 mt-2 rounded w-full">+</button>
                </div>
            @endforeach
        </div>

        {{-- KOLOM KANAN: KERANJANG --}}
        <div class="w-1/3 bg-white text-black rounded p-4 shadow">
            <h2 class="text-lg font-bold mb-2">Keranjang</h2>
            <div id="cart-items" class="mb-4 text-sm">
                <p class="text-center text-gray-500">Belum ada item</p>
            </div>

            <form method="POST" action="{{ route('pos.checkout') }}">
                @csrf
                <input type="hidden" name="items" id="items-input">

                <label class="font-bold">Metode Pembayaran</label>
                <select name="payment_method_id" class="w-full border p-1 rounded mb-2">
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name_payment }}</option>
                    @endforeach
                </select>

                <label class="font-bold">Jumlah Bayar</label>
                <input type="number" name="paid" id="paid-input" class="w-full border p-1 rounded mb-2" required>

                <div class="mb-2"><strong>Total:</strong> <span id="total-price">IDR 0</span></div>
                <div class="mb-4"><strong>Kembalian:</strong> <span id="change">IDR 0</span></div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Proses Pembayaran</button>
                <button type="button" onclick="resetCart()" class="bg-red-600 text-white px-4 py-2 rounded w-full mt-2">Reset</button>
            </form>
        </div>
    </div>

    {{-- Script Keranjang --}}
    <script>
        let cart = [];

        function addToCart(id, name, price) {
            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1 });
            }
            renderCart();
        }

        function resetCart() {
            cart = [];
            document.getElementById('paid-input').value = '';
            renderCart();
        }

        function renderCart() {
            const cartEl = document.getElementById('cart-items');
            const totalEl = document.getElementById('total-price');
            const changeEl = document.getElementById('change');
            const paidInput = document.getElementById('paid-input');
            const itemsInput = document.getElementById('items-input');

            cartEl.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                cartEl.innerHTML = '<p class="text-center text-gray-500">Belum ada item</p>';
            } else {
                cart.forEach(item => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    cartEl.innerHTML += `
                        <div class="flex justify-between mb-1">
                            <div>${item.name} x ${item.quantity}</div>
                            <div>IDR ${subtotal}</div>
                        </div>
                    `;
                });
            }

            totalEl.innerText = 'IDR ' + total.toLocaleString();
            const bayar = parseInt(paidInput.value || 0);
            changeEl.innerText = 'IDR ' + (bayar - total).toLocaleString();

            itemsInput.value = JSON.stringify(cart);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('paid-input').addEventListener('input', renderCart);
        });
    </script>
</body>
</html>
