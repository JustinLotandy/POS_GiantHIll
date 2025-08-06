<!-- resources/views/pos/struk.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk</title>
    <style>
        @media print {
        @page {
            size: 58mm auto; /* 58mm lebar, panjang otomatis */
            margin: 0;
        }
        body { font-family: monospace; font-size: 10px; margin: 0; padding: 0; }
       .invoice { width: 58mm !important; margin: 0 auto !important; padding: 0 4px !important;
        }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { padding: 2px 0; text-align: left; }
        .dashed { border-top: 1px dashed black; margin: 6px 0; }
        .center { text-align: center; font-weight: bold; }
        .right { text-align: right; }
    }
    </style>
</head>
<body>
<div class="invoice">
    <div class="center">Gianthill <br>INVOICE</div>
    <div class="center" style="font-size: 9px;">{{ now()->format('Y-m-d H:i:s') }}</div>
    <div class="dashed"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['item'] }}</td>
                    <td class="right">{{ $item['quantity'] }}</td>
                    <td class="right">{{ number_format($item['price']) }}</td>
                    <td class="right">{{ number_format($item['subtotal']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed"></div>
    <table>
        <tr>
            <td>Total</td>
            <td class="right" colspan="3">{{ number_format($transaction->total) }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right" colspan="3">{{ number_format($transaction->paid) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right" colspan="3">{{ number_format($transaction->change) }}</td>
        </tr>
    </table>
    <div class="dashed"></div>
    <div class="center" style="margin-top: 6px;">
        Terima kasih atas pembelian Anda!
    </div>
</div>
<script>
    window.onload = function () {
        window.print();

        // Deteksi Selesai Print/CANCEL/Close
        window.onafterprint = function () {
            // Redirect ke halaman transaksi (ganti dengan route transaksi kamu)
            window.location.href = "{{ route('pos.index') }}";
        };
    };
</script>
</body>
</html>
