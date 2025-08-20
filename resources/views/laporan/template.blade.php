<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #333; padding: 6px 4px; text-align: left; vertical-align: top; }
        th { background: #eee; }
        .right { text-align: right; }
        .summary { margin-top: 8px; display: table; width: 100%; }
        .box { display: table-cell; padding: 8px; border: 1px solid #333; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div>Periode: <b>{{ $periode }}</b></div>

    <div class="summary">
        <div class="box"><b>Omzet</b><br>Rp {{ number_format($summary['omzet'] ?? 0,0,',','.') }}</div>
        <div class="box"><b>Profit</b><br>Rp {{ number_format($summary['profit'] ?? 0,0,',','.') }}</div>
        <div class="box"><b>Jumlah Transaksi</b><br>{{ number_format($summary['count'] ?? 0) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Produk</th>
                <th>Metode</th>
                <th class="right">Total</th>
                <th class="right">Dibayar</th>
                <th class="right">Kembalian</th>
                <th class="right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @php($gTotal = 0)
            @php($gProfit = 0)
            @forelse($transaksi as $trx)
                @php($gTotal  += (int)($trx->total ?? 0))
                @php($gProfit += (int)($trx->total_profit ?? 0))
                <tr>
                    <td>{{ $trx->id_Transaction }}</td>
                    <td>{{ optional($trx->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{!! $trx->products_label_html ?? '' !!}</td>
                    <td>{{ $trx->paymentMethod->name_payment ?? '-' }}</td>
                    <td class="right">Rp {{ number_format($trx->total ?? 0,0,',','.') }}</td>
                    <td class="right">Rp {{ number_format($trx->paid ?? 0,0,',','.') }}</td>
                    <td class="right">Rp {{ number_format($trx->change ?? 0,0,',','.') }}</td>
                    <td class="right">Rp {{ number_format($trx->total_profit ?? 0,0,',','.') }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center">Tidak ada data transaksi</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="right">Grand Total</th>
                <th class="right">Rp {{ number_format($gTotal,0,',','.') }}</th>
                <th></th>
                <th></th>
                <th class="right">Rp {{ number_format($gProfit,0,',','.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
